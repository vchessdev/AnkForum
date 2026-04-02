# 📚 HƯỚNG DẪN SỬ DỤNG - AnkForum Features Mới

## 🎯 Tổng Quan

AnkForum giờ đã có 3 tính năng mới chính:
1. **📊 Poll (Bình Chọn)** - Tạo cuộc khảo sát
2. **🔴 Livestream (Phát Trực Tiếp)** - Phát video trực tiếp
3. **📸 Image Upload** - Upload ảnh không lưu server

---

## 1️⃣ **POLL - Bình Chọn** 📊

### 📍 Nơi Tìm Poll

Trên **Trang Chủ (Home Page)**:
- Kéo xuống dưới phần "Bài viết mới"
- Sẽ thấy section **"📊 Poll hot"**
- Hiển thị các poll phổ biến

### 🆕 Tạo Poll Mới

**Cách 1: Từ Trang Chủ**
```
1. Kéo xuống phần Post Composer (khung viết bài)
2. Tìm nút "📊 Poll" (bên cạnh nút Ảnh/Video)
3. Click vào nó
4. Điền thông tin:
   - "Câu hỏi" (bắt buộc) - VD: "Bạn thích ngôn ngữ nào?"
   - "Mô tả" (tùy chọn) - VD: "Hãy chọn ngôn ngữ yêu thích"
   - "Lựa chọn" - Nhập từng lựa chọn
     * JavaScript
     * Python
     * Golang
5. Tùy chọn nâng cao:
   ☑️ "Cho phép chọn nhiều lựa chọn" (nếu muốn)
   📅 "Hết hạn sau" - Chọn thời gian:
      - Không giới hạn
      - 1 giờ
      - 6 giờ
      - 1 ngày
      - 7 ngày
      - 30 ngày
6. Click "Tạo Poll"
7. ✅ Xong! Poll của bạn đã được tạo
```

**Cách 2: Từ URL trực tiếp**
```
Truy cập: https://ankb.work.gd/?page=create-poll
```

### 🗳️ Bình Chọn Trên Poll

```
1. Tìm poll trên trang chủ hoặc trang Poll
2. Nhìn thấy các lựa chọn (options) với % phần trăm
3. Click vào lựa chọn bạn muốn
4. ✅ Bình chọn được ghi nhận ngay lập tức
5. Thấy ✓ "Bạn đã bình chọn"
6. Có thể thấy kết quả real-time cập nhật
```

### 📈 Xem Kết Quả Poll

```
- Mỗi lựa chọn hiển thị:
  📊 Tên lựa chọn
  📈 Số phiếu bình chọn
  💯 Phần trăm (%)
  
- Ví dụ:
  JavaScript       ████████████████ 60% (15/25 phiếu)
  Python           ██████████ 40% (10/25 phiếu)
```

### ⚙️ Tuỳ Chọn Poll

**Multiple Choice (Chọn Nhiều)**
```
✓ Kích hoạt → Users có thể chọn 2+ lựa chọn
✗ Tắt (mặc định) → Users chỉ chọn 1
```

**Poll Expiration (Hết Hạn)**
```
Nếu chọn "1 giờ" → Poll tự động đóng sau 1 giờ
Nếu chọn "Không giới hạn" → Mãi mãi có hiệu lực
```

---

## 2️⃣ **LIVESTREAM - Phát Trực Tiếp** 🔴

### 🎥 Bắt Đầu Livestream

**Bước 1: Vào Broadcast Dashboard**
```
Cách 1: Click nút "🔴 Livestream" trên trang chủ
Cách 2: Truy cập https://ankb.work.gd/?page=broadcast
```

**Bước 2: Điền Thông Tin**
```
Tiêu đề (bắt buộc):
  VD: "Học Lập Trình Live"
  
Mô tả (tùy chọn):
  VD: "Hôm nay học JavaScript cơ bản"
```

**Bước 3: Bấm Nút**
```
Click: "🔴 Bắt đầu phát trực tiếp"
```

**Bước 4: Stream Của Bạn Bắt Đầu**
```
📊 Thấy 3 thông tin chính:
  - 👥 Người xem (Viewers) = 0
  - ⏱️  Thời gian (Duration) = 00:00
  - 💬 Bình luận (Comments) = 0

📋 Xem các thống kê real-time cập nhật
```

**Bước 5: Chia Sẻ Stream URL**
```
Link stream sẽ là:
https://ankb.work.gd/?page=livestream&id=stream_xxxx

📋 Copy link → Gửi cho bạn bè → Họ có thể xem
```

**Bước 6: Kết Thúc Stream**
```
Khi muốn kết thúc:
1. Click nút "Kết thúc Stream"
2. Confirm (bấm OK)
3. ✅ Stream kết thúc
4. Stream lưu trữ vĩnh viễn trong "Broadcasts"
```

### 👀 Xem Livestream

**Cách 1: Từ Trang Chủ**
```
1. Kéo xuống section "🔴 Đang phát trực tiếp"
2. Thấy thumbnail của streams đang live
3. Click vào để xem
```

**Cách 2: Vào Trang Broadcasts**
```
1. Trang chủ → Click "Xem tất cả" (next to livestream section)
2. Hoặc: https://ankb.work.gd/?page=broadcasts
3. Lọc theo:
   ✅ "Tất cả" - Mọi stream (live + đã kết thúc)
   🔴 "Đang phát" - Chỉ streams còn live
   ⏹️ "Đã kết thúc" - Chỉ streams đã kết thúc
4. Click vào stream bất kỳ
```

### 💬 Chat Trên Livestream

**Khi Xem Stream:**
```
Bên phải màn hình là "Chat trực tiếp"

1. Nhập tin nhắn ở khung chat
2. Click "Gửi" hoặc Enter
3. 💬 Tin nhắn hiển thị ngay cho tất cả viewers
4. Thấy comments của người khác real-time
```

**Lưu Ý:**
```
⚠️ Phải đăng nhập mới chat được
⚠️ Comments công khai cho tất cả viewers
✅ Có thể comment ngay lập tức
```

### 🔴 Live Badge (Biểu Tượng Sống)

**Nhìn Thấy Ở Đâu?**
```
Khi bạn đang livestream:
1. 🔴 LIVE (pulse/nhấp nháy đỏ) xuất hiện trên navbar
2. Bên cạnh tên user của bạn
3. Báo hiệu cho mọi người rằng bạn đang phát
4. Tự động biến mất khi kết thúc stream
```

**Ý Nghĩa:**
```
🔴 LIVE = Bạn đang phát trực tiếp (online)
Không có badge = Bạn offline
```

---

## 3️⃣ **IMAGE UPLOAD - Upload Ảnh** 📸

### 🖼️ Cách Upload Ảnh

**Trên Trang Chủ:**
```
1. Click "📸 Ảnh/Video" hoặc "Ảnh" trên Post Composer
2. Chọn ảnh từ máy
3. Preview ảnh (xem trước)
4. Viết caption/nội dung bài
5. Click "Đăng" → ✅ Bài được đăng với ảnh
```

**Điểm Khác Biệt:**
```
❌ Cũ: Ảnh được lưu trên server → Quá tải
✅ Mới: Ảnh dạng data URL → Không lưu server
         → Nhanh hơn, đỡ quá tải server
```

**Ảnh Hỗ Trợ:**
```
✅ JPG/JPEG
✅ PNG
✅ GIF
✅ WebP
❌ Không hỗ trợ: RAW, PSD, ...
```

**Giới Hạn:**
```
📦 Max 500MB / file
📁 Max 10 files / bài viết
```

---

## 🎯 **CASE STUDY - Ví Dụ Thực Tế**

### 📊 Poll: Khảo Sát Ngôn Ngữ Lập Trình

```
👤 Bạn muốn biết cộng đồng thích ngôn ngữ nào

Bước 1: Tạo Poll
├─ Câu hỏi: "Ngôn ngữ lập trình nào bạn thích nhất?"
├─ Lựa chọn: JavaScript, Python, Go, Rust, Java
├─ Hết hạn: 7 ngày
└─ Multiple: Không (chỉ chọn 1)

Bước 2: Share
└─ Poll hiển thị trên trang chủ

Bước 3: Xem Kết Quả Sau 7 Ngày
├─ Python:    40% (400 phiếu)
├─ JavaScript: 35% (350 phiếu)
├─ Go:        15% (150 phiếu)
├─ Rust:       7%  (70 phiếu)
└─ Java:       3%  (30 phiếu)
```

### 🔴 Livestream: Tutorial Lập Trình

```
👤 Bạn muốn dạy JavaScript live

Bước 1: Bắt Đầu
├─ Tiêu đề: "Tutorial JavaScript Từ Cơ Bản"
├─ Mô tả: "Học JS cơ bản, array, object, function"
└─ Click "Bắt Đầu"

Bước 2: Phát Trực Tiếp
├─ Mở Visual Studio Code
├─ Share màn hình (browser/desktop)
├─ Giảng bài
└─ Chat với viewers

Bước 3: Kết Thúc
├─ Duration: 2:34:15 (2 tiếng 34 phút 15 giây)
├─ Total viewers: 150 người
├─ Comments: 287 tin nhắn
└─ Stream lưu vĩnh viễn

Bước 4: Sharing
└─ Copy URL → Gửi cho bạn bè → Họ xem lại bất kỳ lúc nào
```

---

## ⚡ **SHORTCUTS & TIPS**

### Nhanh Nhất

```
Tạo Poll nhanh:
1. Home → Click "Poll" → Điền 3 phút → Done

Livestream nhanh:
1. Home → Click "Livestream" → 10 giây → LIVE

Xem Poll/Stream:
1. Home → Scroll → Click → Done
```

### Mẹo Hay

```
📊 Poll Tips:
  - Tiêu đề nên <20 từ
  - 2-4 lựa chọn tốt nhất
  - Multiple choice cho survey mở
  - Set hạn cho khảo sát time-sensitive

🔴 Livestream Tips:
  - Title rõ ràng + keyword (SEO)
  - Kiểm tra kết nối internet trước
  - Chat interactive = viewers nhiều hơn
  - Lên lịch stream → thông báo trước

📸 Upload Tips:
  - Ảnh <2MB tốt nhất
  - PNG for transparency, JPG for photo
  - Thêm caption → engagement cao hơn
```

---

## 🚨 **TROUBLESHOOTING**

### ❌ Poll không hiển thị

```
Nguyên nhân: Chưa đăng nhập hoặc poll mới
Cách fix:
1. ✅ Đăng nhập trước
2. Làm lại từ đầu
3. Refresh page (F5)
```

### ❌ Livestream không phát được

```
Nguyên nhân: Chưa cấp quyền camera/mic
Cách fix:
1. Kiểm tra browser settings
2. Allow camera + microphone
3. Thử browser khác (Chrome, Firefox)
```

### ❌ Không thể bình chọn poll

```
Nguyên nhân: Chưa đăng nhập
Cách fix:
1. Click "Đăng nhập"
2. Nhập username + password
3. Quay lại poll → Bình chọn lại
```

### ❌ Ảnh upload không hiển thị

```
Nguyên nhân: File quá lớn hoặc format lạ
Cách fix:
1. Thử ảnh khác (JPG/PNG)
2. Nén ảnh (Tinify, ImageOptimizer)
3. Max 500MB / file
```

---

## 🔐 **PRIVACY & SECURITY**

### Ai Có Thể Thấy?

```
📊 Poll:
  ✅ Ai cũng xem được
  ✅ Tên người tạo: hiển thị
  ✅ Voter names: ẩn (chỉ thấy số phiếu)

🔴 Livestream:
  ✅ Ai cũng xem được
  ✅ Title + description: công khai
  ✅ Viewer count: hiển thị
  ✅ Comments: công khai

📸 Ảnh:
  ✅ Upload công khai (không private)
  ✅ Ai cũng download được
```

### Bảo Vệ

```
✅ Tất cả actions có CSRF protection
✅ Input được sanitize (xóa script)
✅ Phải login để vote/comment
✅ Auto ban spam keywords
```

---

## 📞 **CONTACT & SUPPORT**

### Có Vấn Đề?

```
🐛 Bug report: Inbox admin
💡 Feature request: Comment bài viết
❓ Câu hỏi: Hỏi trực tiếp trên forum
```

---

## 🎓 **QUICK START (5 PHÚT)**

```
1️⃣  TẠO POLL (2 PHÚT)
    Home → Poll → Điền Q + Options → Tạo Poll
    
2️⃣  LIVESTREAM (2 PHÚT)
    Home → Livestream → Title → Bắt Đầu
    
3️⃣  VOTE & WATCH (1 PHÚT)
    Home → Click Poll/Stream → Done!
```

---

**Enjoy AnkForum mới! 🎉**

Questions? 👉 Hỏi admin hoặc comment trên forum!

---

## 📺 Tính Năng Chia Sẻ Màn Hình (Screen Sharing)

### Giới Thiệu
Khi đang phát trực tiếp, bạn có thể chia sẻ màn hình của mình với người xem. Tính năng này rất hữu ích cho:
- Hướng dẫn sử dụng phần mềm
- Trình chiếu
- Chia sẻ tài liệu
- Streaming games
- Hội thảo trực tuyến

### Cách Sử Dụng

#### Cho Người Phát (Streamer)

1. **Bắt đầu phát trực tiếp**
   - Vào trang "Phát trực tiếp" (/?page=broadcast)
   - Nhấp "🔴 Bắt đầu phát trực tiếp"
   - Nhập tiêu đề và mô tả
   - Nhấp "Bắt đầu"

2. **Chia sẻ màn hình**
   - Khi stream đang chạy, nhấp nút "📺 Chia sẻ màn hình"
   - Trình duyệt sẽ hiện hộp thoại chọn màn hình
   - Chọn màn hình (Monitor) hoặc cửa sổ ứng dụng
   - Nhấp "Chia sẻ" để xác nhận
   - Nút sẽ chuyển sang màu xanh "✅ Dừng chia sẻ màn hình"

3. **Dừng chia sẻ**
   - Nhấp nút "✅ Dừng chia sẻ màn hình" (bây giờ xanh lá)
   - Hoặc dừng từ menu hệ thống (Windows/Mac)
   - Nút sẽ quay lại màu xanh dương "📺 Chia sẻ màn hình"

#### Cho Người Xem (Viewer)

1. **Xem livestream với screen sharing**
   - Truy cập trang livestream (/?page=livestream&id=STREAM_ID)
   - Nếu streamer đang chia sẻ màn hình, bạn sẽ thấy:
     - Badge xanh "📺 Chia sẻ màn hình" ở góc trên trái
     - Màn hình được chia sẻ sẽ hiển thị trong vùng video

2. **Xem trong carousel trang chủ**
   - Trên trang chủ, livestream có screen sharing sẽ có badge
   - Badge "📺 Screen Share" ở góc dưới trái của thumbnail
   - Nhấp để xem stream đầy đủ

### Yêu Cầu & Tính Năng

#### Trình Duyệt Hỗ Trợ
- Chrome/Chromium 72+
- Firefox 66+
- Edge 79+
- Safari 13+ (macOS)
- Opera 60+

#### Yêu Cầu Quyền
- Trình duyệt sẽ yêu cầu quyền chia sẻ màn hình
- Bạn phải cấp quyền để bắt đầu chia sẻ
- Quyền được hỏi mỗi lần chia sẻ (an toàn)

#### Tính Năng
✅ Chia sẻ một hay nhiều màn hình
✅ Chia sẻ cửa sổ ứng dụng cụ thể
✅ Hiển thị con trỏ chuột cho người xem
✅ Dừng tại bất kỳ lúc nào
✅ Tự động phát hiện nếu người dùng dừng chia sẻ từ menu hệ thống

### Hướng Dẫn Chi Tiết

#### Windows
1. Nhấp "📺 Chia sẻ màn hình"
2. Chọn giữa:
   - **Monitor**: Chia sẻ toàn bộ màn hình
   - **Window**: Chia sẻ cửa sổ ứng dụng cụ thể
3. Nhấp "Chia sẻ"
4. Để dừng:
   - Nhấp nút "✅ Dừng chia sẻ màn hình", HOẶC
   - Nhấp icon dừng chia sẻ ở taskbar (Chrome/Edge)

#### macOS
1. Nhấp "📺 Chia sẻ màn hình"
2. Chọn:
   - **Entire Screen**: Chia sẻ toàn bộ
   - **Window**: Chia sẻ cửa sổ
3. Cho phép quyền truy cập nếu được hỏi
4. Nhấp "Chia sẻ" để xác nhận
5. Để dừng: Nhấp nút xanh "✅ Dừng chia sẻ màn hình"

#### Linux
1. Nhấp "📺 Chia sẻ màn hình"
2. Chọn monitor hoặc cửa sổ
3. Xác nhận quyền nếu cần
4. Dừng bằng nút "✅ Dừng chia sẻ màn hình"

### Ví Dụ Sử Dụng

#### Ví Dụ 1: Hướng Dẫn Phần Mềm
```
1. Bắt đầu stream: "Hướng dẫn sử dụng Photoshop"
2. Nhấp "Chia sẻ màn hình"
3. Chọn cửa sổ Photoshop
4. Hướng dẫn từng bước cho người xem
5. Dừng khi xong: "Cảm ơn các bạn"
6. Nhấp "Kết thúc Stream"
```

#### Ví Dụ 2: Streaming Game
```
1. Tạo stream: "Gaming Session - Elden Ring"
2. Chia sẻ màn hình (chọn Monitor)
3. Chơi game để người xem theo dõi
4. Chat với người xem qua sidebar
5. Dừng chia sẻ để chỉ hiển thị webcam (nếu có)
6. Kết thúc stream khi xong
```

#### Ví Dụ 3: Hội Thảo Trực Tuyến
```
1. Stream: "Hội thảo: Kiếm tiền Online"
2. Chia sẻ slides PowerPoint
3. Trình chiếu toàn bộ (80 slides)
4. Người xem tương tác qua chat
5. Dừng chia sẻ, mở đàm thoại
6. Kết thúc
```

### Lưu Ý Quan Trọng

⚠️ **Quyền Riêng Tư**
- Chia sẻ từng cửa sổ để tránh lộ thông tin nhạy cảm
- Tối thiểu hóa cửa số background
- Kiểm tra tất cả tab trình duyệt trước khi chia sẻ
- Đóng thông báo nhạy cảm trước khi chia sẻ

⚠️ **Hiệu Suất**
- Chia sẻ màn hình tiêu tốn nhiều tài nguyên
- Đóng các ứng dụng nặng khác
- Giảm chất lượng video nếu lag
- Kiểm tra độ mạnh kết nối Internet

⚠️ **Kỹ Thuật**
- Một lần chỉ chia sẻ một màn hình/cửa sổ
- Người xem thấy con trỏ chuột của bạn
- Âm thanh không được chia sẻ (chỉ là chứng chỉ)
- Dừng chia sẻ sẽ không kết thúc stream

### Xử Lý Sự Cố

| Vấn Đề | Nguyên Nhân | Giải Pháp |
|--------|-----------|----------|
| Không thấy nút chia sẻ | Stream chưa bắt đầu | Bắt đầu stream trước |
| Trình duyệt không yêu cầu quyền | Đã từ chối quyền | Đặt lại quyền trong Settings trình duyệt |
| Chia sẻ bị dừng đột ngột | Lỗi kết nối | Kiểm tra Internet, thử lại |
| Người xem không thấy | Màn hình chưa được chia sẻ | Bắt đầu chia sẻ again |
| Giao diện chậm | Sử dụng quá nhiều tài nguyên | Đóng ứng dụng nặng |

### FAQ

**Q: Có giới hạn thời gian chia sẻ không?**
A: Không. Bạn có thể chia sẻ lâu miễn là stream còn chạy.

**Q: Người xem có thể điều khiển màn hình của tôi không?**
A: Không, đây chỉ là xem không thể tương tác.

**Q: Tôi có thể dừng chia sẻ mà không kết thúc stream không?**
A: Có, nhấp nút "Dừng chia sẻ màn hình" để chỉ dừng chia sẻ, stream vẫn chạy.

**Q: Âm thanh của màn hình chia sẻ có được ghi âm không?**
A: Không, chỉ video được chia sẻ. Âm thanh từ OBS hoặc mixer.

**Q: Tôi có thể chia sẻ nhiều màn hình không?**
A: Một lần chỉ một, nhưng có thể chuyển đổi giữa các màn hình.

**Q: Trình duyệt nào tốt nhất cho chia sẻ màn hình?**
A: Chrome/Edge có hiệu suất tốt nhất. Firefox cũng ổn định.

