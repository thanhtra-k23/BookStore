# ERD - Sơ Đồ Cơ Sở Dữ Liệu Hệ Thống Nhà Sách

## 1. Tổng Quan

Hệ thống sử dụng **12 bảng chính** với các mối quan hệ được thiết kế theo chuẩn 3NF.

## 2. Sơ Đồ ERD (Text-based)

```
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│   THE_LOAI      │       │     SACH        │       │    TAC_GIA      │
├─────────────────┤       ├─────────────────┤       ├─────────────────┤
│ PK ma_the_loai  │──┐    │ PK ma_sach      │    ┌──│ PK ma_tac_gia   │
│    ten_the_loai │  │    │    ten_sach     │    │  │    ten_tac_gia  │
│    duong_dan    │  │    │    duong_dan    │    │  │    duong_dan    │
│    mo_ta        │  │    │    mo_ta        │    │  │    tieu_su      │
│    hinh_anh     │  └───>│ FK ma_the_loai  │<───┘  │    hinh_anh     │
│ FK parent_id    │       │ FK ma_tac_gia   │       │    quoc_tich    │
│    trang_thai   │       │ FK ma_nxb       │       └─────────────────┘
└─────────────────┘       │    gia_ban      │
                          │    gia_khuyen_mai│       ┌─────────────────┐
                          │    so_luong_ton │       │  NHA_XUAT_BAN   │
                          │    hinh_anh     │       ├─────────────────┤
                          │    trang_thai   │    ┌──│ PK ma_nxb       │
                          │    luot_xem     │    │  │    ten_nxb      │
                          └────────┬────────┘    │  │    dia_chi      │
                                   │             │  │    dien_thoai   │
                                   │<────────────┘  │    email        │
                                   │                └─────────────────┘
        ┌──────────────────────────┼──────────────────────────┐
        │                          │                          │
        ▼                          ▼                          ▼
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│   GIO_HANG      │       │   YEU_THICH     │       │   DANH_GIA      │
├─────────────────┤       ├─────────────────┤       ├─────────────────┤
│ PK ma_gio_hang  │       │ PK ma_yeu_thich │       │ PK ma_danh_gia  │
│ FK ma_nguoi_dung│       │ FK ma_nguoi_dung│       │ FK ma_nguoi_dung│
│ FK ma_sach      │       │ FK ma_sach      │       │ FK ma_sach      │
│    so_luong     │       │    created_at   │       │    diem_danh_gia│
│    created_at   │       └─────────────────┘       │    noi_dung     │
└─────────────────┘                                 │    trang_thai   │
        │                                           └─────────────────┘
        │
        ▼
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│  NGUOI_DUNG     │       │    DON_HANG     │       │ CHI_TIET_DON    │
│    (USERS)      │       ├─────────────────┤       │     _HANG       │
├─────────────────┤       │ PK ma_don_hang  │       ├─────────────────┤
│ PK ma_nguoi_dung│──────>│ FK ma_nguoi_dung│       │ PK ma_chi_tiet  │
│    ho_ten       │       │ FK ma_giam_gia  │<──┐   │ FK ma_don_hang  │
│    email        │       │    ma_don       │   │   │ FK ma_sach      │
│    password     │       │    tong_tien    │   │   │    so_luong     │
│    so_dien_thoai│       │    trang_thai   │   │   │    don_gia      │
│    dia_chi      │       │    dia_chi_giao │   │   │    thanh_tien   │
│    vai_tro      │       │    ghi_chu      │   │   └────────┬────────┘
│    trang_thai   │       └────────┬────────┘   │            │
└─────────────────┘                │             │            │
                                   │             │            │
                                   ▼             │            ▼
                          ┌─────────────────┐    │   ┌─────────────────┐
                          │  MA_GIAM_GIA    │    │   │      SACH       │
                          ├─────────────────┤    │   │   (reference)   │
                          │ PK ma_giam_gia  │────┘   └─────────────────┘
                          │    ma_code      │
                          │    loai_giam    │
                          │    gia_tri      │
                          │    so_luong     │
                          │    ngay_bat_dau │
                          │    ngay_ket_thuc│
                          │    trang_thai   │
                          └─────────────────┘
```

## 3. Chi Tiết Các Bảng

### 3.1 Bảng `users` (Người dùng)
```sql
CREATE TABLE users (
    ma_nguoi_dung    BIGINT PRIMARY KEY AUTO_INCREMENT,
    ho_ten           VARCHAR(255) NOT NULL,
    email            VARCHAR(255) UNIQUE NOT NULL,
    password         VARCHAR(255) NOT NULL,
    so_dien_thoai    VARCHAR(20),
    dia_chi          TEXT,
    vai_tro          ENUM('admin', 'user') DEFAULT 'user',
    trang_thai       ENUM('active', 'inactive') DEFAULT 'active',
    avatar           VARCHAR(255),
    email_verified_at TIMESTAMP NULL,
    remember_token   VARCHAR(100),
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP,
    deleted_at       TIMESTAMP NULL
);
```

### 3.2 Bảng `the_loai` (Thể loại)
```sql
CREATE TABLE the_loai (
    ma_the_loai      BIGINT PRIMARY KEY AUTO_INCREMENT,
    ten_the_loai     VARCHAR(255) NOT NULL,
    duong_dan        VARCHAR(255) UNIQUE NOT NULL,
    mo_ta            TEXT,
    hinh_anh         VARCHAR(255),
    parent_id        BIGINT NULL REFERENCES the_loai(ma_the_loai),
    trang_thai       BOOLEAN DEFAULT TRUE,
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP,
    deleted_at       TIMESTAMP NULL
);
```

### 3.3 Bảng `tac_gia` (Tác giả)
```sql
CREATE TABLE tac_gia (
    ma_tac_gia       BIGINT PRIMARY KEY AUTO_INCREMENT,
    ten_tac_gia      VARCHAR(255) NOT NULL,
    duong_dan        VARCHAR(255) UNIQUE NOT NULL,
    tieu_su          TEXT,
    hinh_anh         VARCHAR(255),
    quoc_tich        VARCHAR(100),
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP,
    deleted_at       TIMESTAMP NULL
);
```

### 3.4 Bảng `nha_xuat_ban` (Nhà xuất bản)
```sql
CREATE TABLE nha_xuat_ban (
    ma_nxb           BIGINT PRIMARY KEY AUTO_INCREMENT,
    ten_nxb          VARCHAR(255) NOT NULL,
    dia_chi          TEXT,
    dien_thoai       VARCHAR(20),
    email            VARCHAR(255),
    website          VARCHAR(255),
    mo_ta            TEXT,
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP,
    deleted_at       TIMESTAMP NULL
);
```

### 3.5 Bảng `sach` (Sách)
```sql
CREATE TABLE sach (
    ma_sach          BIGINT PRIMARY KEY AUTO_INCREMENT,
    ten_sach         VARCHAR(255) NOT NULL,
    duong_dan        VARCHAR(255) UNIQUE NOT NULL,
    mo_ta            TEXT,
    noi_dung         LONGTEXT,
    hinh_anh         VARCHAR(255),
    gia_ban          DECIMAL(12,0) NOT NULL,
    gia_khuyen_mai   DECIMAL(12,0) NULL,
    so_luong_ton     INT DEFAULT 0,
    ma_the_loai      BIGINT NOT NULL REFERENCES the_loai(ma_the_loai),
    ma_tac_gia       BIGINT NOT NULL REFERENCES tac_gia(ma_tac_gia),
    ma_nxb           BIGINT NULL REFERENCES nha_xuat_ban(ma_nxb),
    nam_xuat_ban     INT,
    trang_thai       ENUM('active', 'inactive') DEFAULT 'active',
    luot_xem         INT DEFAULT 0,
    diem_trung_binh  DECIMAL(3,2) DEFAULT 0,
    so_luot_danh_gia INT DEFAULT 0,
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP,
    deleted_at       TIMESTAMP NULL,
    
    INDEX idx_the_loai (ma_the_loai),
    INDEX idx_tac_gia (ma_tac_gia),
    INDEX idx_trang_thai (trang_thai),
    INDEX idx_gia_ban (gia_ban)
);
```

### 3.6 Bảng `don_hang` (Đơn hàng)
```sql
CREATE TABLE don_hang (
    ma_don_hang      BIGINT PRIMARY KEY AUTO_INCREMENT,
    ma_don           VARCHAR(50) UNIQUE NOT NULL,
    ma_nguoi_dung    BIGINT NOT NULL REFERENCES users(ma_nguoi_dung),
    ma_giam_gia      BIGINT NULL REFERENCES ma_giam_gia(ma_giam_gia),
    tong_tien_hang   DECIMAL(12,0) NOT NULL,
    tien_giam        DECIMAL(12,0) DEFAULT 0,
    phi_van_chuyen   DECIMAL(12,0) DEFAULT 0,
    tong_tien        DECIMAL(12,0) NOT NULL,
    ho_ten_nguoi_nhan VARCHAR(255) NOT NULL,
    so_dien_thoai    VARCHAR(20) NOT NULL,
    dia_chi_giao     TEXT NOT NULL,
    ghi_chu          TEXT,
    phuong_thuc_thanh_toan VARCHAR(50) DEFAULT 'cod',
    trang_thai       ENUM('cho_xac_nhan', 'da_xac_nhan', 'dang_giao', 'da_giao', 'da_huy') DEFAULT 'cho_xac_nhan',
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP,
    deleted_at       TIMESTAMP NULL,
    
    INDEX idx_nguoi_dung (ma_nguoi_dung),
    INDEX idx_trang_thai (trang_thai),
    INDEX idx_created_at (created_at)
);
```

### 3.7 Bảng `chi_tiet_don_hang` (Chi tiết đơn hàng)
```sql
CREATE TABLE chi_tiet_don_hang (
    ma_chi_tiet      BIGINT PRIMARY KEY AUTO_INCREMENT,
    ma_don_hang      BIGINT NOT NULL REFERENCES don_hang(ma_don_hang),
    ma_sach          BIGINT NOT NULL REFERENCES sach(ma_sach),
    so_luong         INT NOT NULL,
    don_gia          DECIMAL(12,0) NOT NULL,
    thanh_tien       DECIMAL(12,0) NOT NULL,
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP,
    
    INDEX idx_don_hang (ma_don_hang),
    INDEX idx_sach (ma_sach)
);
```

### 3.8 Bảng `gio_hang` (Giỏ hàng)
```sql
CREATE TABLE gio_hang (
    ma_gio_hang      BIGINT PRIMARY KEY AUTO_INCREMENT,
    ma_nguoi_dung    BIGINT NOT NULL REFERENCES users(ma_nguoi_dung),
    ma_sach          BIGINT NOT NULL REFERENCES sach(ma_sach),
    so_luong         INT NOT NULL DEFAULT 1,
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP,
    
    UNIQUE KEY unique_cart (ma_nguoi_dung, ma_sach)
);
```

### 3.9 Bảng `yeu_thich` (Danh sách yêu thích)
```sql
CREATE TABLE yeu_thich (
    ma_yeu_thich     BIGINT PRIMARY KEY AUTO_INCREMENT,
    ma_nguoi_dung    BIGINT NOT NULL REFERENCES users(ma_nguoi_dung),
    ma_sach          BIGINT NOT NULL REFERENCES sach(ma_sach),
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP,
    
    UNIQUE KEY unique_wishlist (ma_nguoi_dung, ma_sach)
);
```

### 3.10 Bảng `danh_gia` (Đánh giá sách)
```sql
CREATE TABLE danh_gia (
    ma_danh_gia      BIGINT PRIMARY KEY AUTO_INCREMENT,
    ma_nguoi_dung    BIGINT NOT NULL REFERENCES users(ma_nguoi_dung),
    ma_sach          BIGINT NOT NULL REFERENCES sach(ma_sach),
    diem_danh_gia    TINYINT NOT NULL CHECK (diem_danh_gia BETWEEN 1 AND 5),
    noi_dung         TEXT,
    trang_thai       ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP,
    
    UNIQUE KEY unique_review (ma_nguoi_dung, ma_sach)
);
```

### 3.11 Bảng `ma_giam_gia` (Mã giảm giá)
```sql
CREATE TABLE ma_giam_gia (
    ma_giam_gia      BIGINT PRIMARY KEY AUTO_INCREMENT,
    ma_code          VARCHAR(50) UNIQUE NOT NULL,
    mo_ta            TEXT,
    loai_giam        ENUM('phan_tram', 'co_dinh') NOT NULL,
    gia_tri          DECIMAL(12,2) NOT NULL,
    gia_tri_toi_thieu DECIMAL(12,0) DEFAULT 0,
    giam_toi_da      DECIMAL(12,0) NULL,
    so_luong         INT NULL,
    so_lan_su_dung   INT DEFAULT 0,
    ngay_bat_dau     DATE NOT NULL,
    ngay_ket_thuc    DATE NOT NULL,
    trang_thai       BOOLEAN DEFAULT TRUE,
    created_at       TIMESTAMP,
    updated_at       TIMESTAMP,
    deleted_at       TIMESTAMP NULL
);
```

## 4. Mối Quan Hệ (Relationships)

### 4.1 One-to-Many (1-N)
| Bảng cha | Bảng con | Mô tả |
|----------|----------|-------|
| the_loai | sach | Một thể loại có nhiều sách |
| tac_gia | sach | Một tác giả có nhiều sách |
| nha_xuat_ban | sach | Một NXB có nhiều sách |
| users | don_hang | Một người dùng có nhiều đơn hàng |
| users | gio_hang | Một người dùng có nhiều items giỏ hàng |
| users | yeu_thich | Một người dùng có nhiều sách yêu thích |
| users | danh_gia | Một người dùng có nhiều đánh giá |
| don_hang | chi_tiet_don_hang | Một đơn hàng có nhiều chi tiết |
| sach | chi_tiet_don_hang | Một sách có trong nhiều chi tiết đơn |
| the_loai | the_loai | Self-reference (thể loại cha-con) |

### 4.2 Many-to-Many (N-N) thông qua bảng trung gian
| Bảng 1 | Bảng 2 | Bảng trung gian |
|--------|--------|-----------------|
| users | sach | gio_hang |
| users | sach | yeu_thich |
| users | sach | danh_gia |

## 5. Indexes và Tối Ưu

### 5.1 Primary Keys
- Tất cả bảng sử dụng `BIGINT AUTO_INCREMENT`

### 5.2 Foreign Keys
- Đảm bảo tính toàn vẹn dữ liệu
- ON DELETE CASCADE cho chi_tiet_don_hang
- ON DELETE SET NULL cho ma_nxb trong sach

### 5.3 Indexes
- Index trên các cột thường query: trang_thai, ma_the_loai, ma_tac_gia
- Index trên cột sắp xếp: gia_ban, created_at, luot_xem
- Unique index trên: email, duong_dan, ma_code

## 6. Soft Deletes

Các bảng hỗ trợ soft delete (deleted_at):
- users
- sach
- the_loai
- tac_gia
- nha_xuat_ban
- don_hang
- ma_giam_gia

---

*Tài liệu ERD - Hệ thống Nhà Sách Online*
*Cập nhật: 24/12/2024*
