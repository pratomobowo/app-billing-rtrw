# GOWA (Go WhatsApp) Integration Guide

Dokumentasi lengkap implementasi GOWA versi 8.0+ (Multi-Device Support) berdasarkan codebase BantuAI.

## 1. Overview

GOWA adalah WhatsApp Gateway API yang memungkinkan aplikasi untuk berinteraksi dengan WhatsApp secara programatik. Versi yang digunakan (8.0+) mendukung:
- **Multi-Device Support**: Satu nomor bisa login di banyak device/sesi.
- **Device Scoping**: Setiap request API harus menyertakan header `X-Device-ID`.
- **Comprehensive API**: Kirim pesan (teks, gambar, file, lokasi), manajemen grup, status presensi, dll.

## 2. Configuration & Types

### Interface Config
```typescript
interface GowaConfig {
    gatewayUrl: string;  // Base URL instance GOWA (misal: https://wa.example.com)
    username?: string;   // Basic Auth Username
    password?: string;   // Basic Auth Password
    deviceId?: string;   // REQUIRED untuk GOWA 8.0+ (ID unik device/sesi)
}
```

### Authorization Headers
Setiap request ke GOWA WAJIB menggunakan headers berikut:

```typescript
function createHeaders(config: GowaConfig): HeadersInit {
    const headers: HeadersInit = {
        "Content-Type": "application/json",
    };

    // 1. Basic Auth (Optional - jika instance diproteksi)
    if (config.username && config.password) {
        const credentials = Buffer.from(`${config.username}:${config.password}`).toString("base64");
        headers["Authorization"] = `Basic ${credentials}`;
    }

    // 2. Device ID via Header (REQUIRED v8.0+)
    // Tanpa header ini, GOWA tidak tahu "sesi" mana yang harus dipakai mengirim pesan
    if (config.deviceId) {
        headers["X-Device-ID"] = config.deviceId;
    }

    return headers;
}
```

## 3. Core Features Implementation

Berikut adalah fungsi-fungsi inti yang siap pakai (copy-pasteable).

### A. Mengirim Pesan Teks
**Endpoint:** `POST /send/message`

```typescript
interface SendMessageOptions {
    phone: string;
    message: string;
    reply_message_id?: string; // Optional: Reply konteks
    is_forwarded?: boolean;
    duration?: number; // Disappearing message
}

export async function sendMessage(config: GowaConfig, options: SendMessageOptions) {
    // Sanitize phone: Keep only digits (e.g., 628123456789)
    const cleanPhone = options.phone.replace(/[^0-9]/g, "");

    const response = await fetch(`${config.gatewayUrl}/send/message`, {
        method: "POST",
        headers: createHeaders(config),
        body: JSON.stringify({
            phone: cleanPhone,
            message: options.message,
            reply_message_id: options.reply_message_id,
        }),
    });

    return await response.json();
}
```

### B. Mengirim Gambar
**Endpoint:** `POST /send/image`

```typescript
export async function sendImage(config: GowaConfig, phone: string, imageUrl: string, caption: string) {
    const response = await fetch(`${config.gatewayUrl}/send/image`, {
        method: "POST",
        headers: createHeaders(config),
        body: JSON.stringify({
            phone,
            image: imageUrl, // URL gambar public
            caption,
        }),
    });
    return await response.json();
}
```

### C. Chat Presence (Typing Indicator)
**Endpoint:** `POST /send/chat-presence`

Sangat berguna untuk membuat bot terasa lebih "manusiawi".

```typescript
export async function sendChatPresence(config: GowaConfig, phone: string, action: "start" | "stop") {
    await fetch(`${config.gatewayUrl}/send/chat-presence`, {
        method: "POST",
        headers: createHeaders(config),
        body: JSON.stringify({
            phone,
            action, // "start" = typing..., "stop" = berhenti
        }),
    });
}
```

### D. Mark as Read (Centang Biru)
**Endpoint:** `POST /message/{id}/read`

```typescript
export async function markAsRead(config: GowaConfig, phone: string, messageId: string) {
    await fetch(`${config.gatewayUrl}/message/${messageId}/read`, {
        method: "POST",
        headers: createHeaders(config),
        body: JSON.stringify({ phone }),
    });
}
```

## 4. Device Management (Auth Flow)

Untuk menghubungkan nomor WhatsApp baru:

### 1. Buat Device Baru
Sebelum bisa generate QR, create device dulu di GOWA.

```typescript
export async function createDevice(config: GowaConfig, newDeviceId: string) {
    await fetch(`${config.gatewayUrl}/devices`, {
        method: "POST",
        headers: createHeaders(config), // Gunakan akun admin/master
        body: JSON.stringify({
            id: newDeviceId,
            device_id: newDeviceId
        }),
    });
}
```

### 2. Dapatkan QR Code
Setelah device dibuat, minta QR code untuk discan user.

```typescript
export async function getLoginQR(config: GowaConfig) {
    // Config harus berisi deviceId yang baru dibuat tadi
    const response = await fetch(`${config.gatewayUrl}/app/login`, {
        headers: createHeaders(config), // Header X-Device-ID PENTING di sini
    });
    const data = await response.json();
    return data.results; // Berisi { qr_link: "base64...", qr_duration: 60 }
}
```

### 3. Cek Status Login
```typescript
export async function getStatus(config: GowaConfig) {
    const response = await fetch(`${config.gatewayUrl}/app/status`, {
        headers: createHeaders(config),
    });
    return await response.json();
}
```

## 5. Webhook Handling (Menerima Pesan)

GOWA akan mengirim POST request ke endpoint webhook yang dikonfigurasi.

### Struktur Payload (GOWA 8.0+)
Payload bisa bervariasi, pastikan handle kedua struktur ini:

**Structure A (Nested Payload):**
```json
{
  "event": "message",
  "device_id": "unique-device-id", 
  "payload": {
    "id": "MSG_ID",
    "sender_id": "628xxx@s.whatsapp.net",
    "body": "Halo bot",
    "from": "628xxx@s.whatsapp.net",
    "pushname": "User Name"
  }
}
```

**Structure B (Flat Payload - Legacy/Alternative):**
```json
{
  "message": { "text": "Halo bot", "id": "MSG_ID" },
  "sender_id": "628xxx@s.whatsapp.net",
  "pushname": "User Name"
}
```

### Contoh Implementation (Next.js App Router)

```typescript
// src/app/api/webhook/route.ts

export async function POST(request: NextRequest) {
    const rawBody = await request.text();
    const payload = JSON.parse(rawBody);

    // 1. Normalize Event & Data
    const eventType = payload.event || "message";
    const data = payload.payload || payload;

    // 2. Filter hanya message event
    if (eventType !== "message") return NextResponse.json({ status: "skipped" });

    // 3. Extract Info Penting
    const messageText = data.body || data.message?.text;
    const senderId = data.sender_id || payload.sender_id; // "628xxx@s.whatsapp.net"
    const pushname = data.pushname || data.from_name;
    const deviceId = request.headers.get("x-device-id") || data.device_id; 

    // 4. Bersihkan Nomor HP
    const phoneNumber = senderId.split("@")[0]; // "628xxx"

    console.log(`Pesan dari ${pushname} (${phoneNumber}): ${messageText}`);
    console.log(`Diterima di device: ${deviceId}`);

    // ... Lakukan logika bot / processing di sini ...

    return NextResponse.json({ status: "ok" });
}
```

### Security: Webhook Signature Check
Selalu verifikasi signature jika GOWA dikonfigurasi dengan secret key.

```typescript
import { createHmac } from "crypto";

function verifySignature(payload: string, signature: string, secret: string): boolean {
    const expected = "sha256=" + createHmac("sha256", secret).update(payload).digest("hex");
    return signature === expected;
}
```

## 6. Common Issues & Solusi

### 1. Pesan Tidak Terkirim (Cross-talk)
**Masalah**: Anda punya banyak device terhubung ke satu GOWA instance. Anda kirim API request tapi pesan terkirim dari nomor yang SALAH.
**Penyebab**: Lupa menyertakan header `X-Device-ID`. GOWA akan mengambil device acak atau default jika header ini tidak ada.
**Solusi**: Pastikan `createHeaders` selalu dipanggil dengan `deviceId` spesifik.

### 2. Webhook "Bot Not Found"
**Masalah**: Webhook masuk tapi aplikasi tidak tahu pesan ini untuk bot yang mana.
**Solusi**: 
- Cek header incoming webhook: `X-Device-Id`.
- Atau cek payload field: `device_id`.
- Gunakan ID ini untuk mencari bot di database Anda.

### 3. Media Gagal Terkirim
**Masalah**: `sendImage` sukses tapi gambar tidak muncul di WA penerima.
**Solusi**:
- Pastikan URL gambar publik (bisa diakses internet tanpa login).
- Format file didukung (JPEG/PNG).
- Ukuran file tidak terlalu besar (>16MB biasanya gagal).

## 7. Referensi Lengkap

- **API Documentation Resmi**: [GOWA Docs on Bump.sh](https://bump.sh/aldinokemal/doc/go-whatsapp-web-multidevice/)
- **Source Code Wrapper**: Lihat file `src/lib/gowa.ts` di project ini untuk implementasi lengkap TypeScript.
