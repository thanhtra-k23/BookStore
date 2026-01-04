# SƠ ĐỒ HỆ THỐNG - DỰ ÁN NHÀ SÁCH LARAVEL

## MỤC LỤC
1. [Sơ đồ ERD (Entity Relationship Diagram)](#1-sơ-đồ-erd)
2. [Sơ đồ CDM (Conceptual Data Model)](#2-sơ-đồ-cdm)
3. [Sơ đồ PDM (Physical Data Model)](#3-sơ-đồ-pdm)
4. [Sơ đồ Use Case](#4-sơ-đồ-use-case)
5. [Sơ đồ phân cấp chức năng](#5-sơ-đồ-phân-cấp-chức-năng)

---

## 1. SƠ ĐỒ ERD (Entity Relationship Diagram)

### 1.1 Mô tả các thực thể

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           SƠ ĐỒ ERD - NHÀ SÁCH                              │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌──────────────┐         ┌──────────────┐         ┌──────────────┐
    │   TÁC GIẢ    │         │    SÁCH      │         │   THỂ LOẠI   │
    │──────────────│         │──────────────│         │──────────────│
    │ ma_tac_gia   │◄───────┤│ ma_sach      │├───────►│ ma_the_loai  │
    │ ten_tac_gia  │    1:N  │ ten_sach     │  N:1    │ ten_the_loai │
    │ tieu_su      │         │ mo_ta        │         │ mo_ta        │
    │ hinh_anh     │         │ gia_ban      │         │ hinh_anh     │
    │ duong_dan    │         │ gia_khuyen_mai│        │ duong_dan    │
    └──────────────┘         │ so_luong_ton │         └──────────────┘
                             │ ma_tac_gia   │
    ┌──────────────┐         │ ma_the_loai  │         ┌──────────────┐
    │ NHÀ XUẤT BẢN │         │ ma_nxb       │         │  ĐÁNH GIÁ    │
    │──────────────│         │ trang_thai   │         │──────────────│
    │ ma_nxb       │◄───────┤│ luot_xem     │├───────►│ ma_danh_gia  │
    │ ten_nxb      │    1:N  │ diem_tb      │  1:N    │ ma_sach      │
    │ dia_chi      │         └──────┬───────┘         │ ma_nguoi_dung│
    │ so_dien_thoai│                │                 │ diem_so      │
    └──────────────┘                │                 │ noi_dung     │
                                    │                 └──────────────┘
                                    │
                    ┌───────────────┼───────────────┐
                    │               │               │
                    ▼               ▼               ▼
           ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
           │  GIỎ HÀNG    │ │  YÊU THÍCH   │ │ CHI TIẾT ĐH  │
           │──────────────│ │──────────────│ │──────────────│
           │ ma_gio_hang  │ │ ma_yeu_thich │ │ ma_chi_tiet  │
           │ ma_nguoi_dung│ │ ma_nguoi_dung│ │ ma_don_hang  │
           │ ma_sach      │ │ ma_sach      │ │ ma_sach      │
           │ so_luong     │ │ created_at   │ │ so_luong     │
           │ gia_tai_td   │ └──────────────┘ │ gia_ban_td   │
           └──────────────┘                  └──────┬───────┘
                    │                               │
                    │                               │
                    ▼                               ▼
           ┌──────────────┐                ┌──────────────┐
           │  NGƯỜI DÙNG  │                │   ĐƠN HÀNG   │
           │──────────────│                │──────────────│
           │ ma_nguoi_dung│◄──────────────┤│ ma_don_hang  │
           │ ho_ten       │      1:N       │ ma_don       │
           │ email        │                │ ma_nguoi_dung│
           │ mat_khau     │                │ tong_tien    │
           │ so_dien_thoai│                │ trang_thai   │
           │ dia_chi      │                │ dia_chi_giao │
           │ vai_tro      │                │ ma_giam_gia  │
           └──────────────┘                └──────┬───────┘
                                                  │
                                                  │ N:1
                                                  ▼
                                          ┌──────────────┐
                                          │ MÃ GIẢM GIÁ  │
                                          │──────────────│
                                          │ ma_giam_gia  │
                                          │ ma_code      │
                                          │ loai_giam_gia│
                                          │ gia_tri      │
                                          │ ngay_bat_dau │
                                          │ ngay_ket_thuc│
                                          │ so_luong     │
                                          └──────────────┘
```

### 1.2 Mô tả quan hệ

| Quan hệ | Thực thể 1 | Thực thể 2 | Loại | Mô tả |
|---------|------------|------------|------|-------|
| R1 | TÁC GIẢ | SÁCH | 1:N | Một tác giả có nhiều sách |
| R2 | THỂ LOẠI | SÁCH | 1:N | Một thể loại có nhiều sách |
| R3 | NHÀ XUẤT BẢN | SÁCH | 1:N | Một NXB xuất bản nhiều sách |
| R4 | NGƯỜI DÙNG | ĐƠN HÀNG | 1:N | Một người dùng có nhiều đơn hàng |
| R5 | ĐƠN HÀNG | CHI TIẾT ĐH | 1:N | Một đơn hàng có nhiều chi tiết |
| R6 | SÁCH | CHI TIẾT ĐH | 1:N | Một sách có trong nhiều chi tiết |
| R7 | NGƯỜI DÙNG | GIỎ HÀNG | 1:N | Một người dùng có nhiều item giỏ |
| R8 | SÁCH | GIỎ HÀNG | 1:N | Một sách có trong nhiều giỏ |
| R9 | NGƯỜI DÙNG | YÊU THÍCH | 1:N | Một người dùng yêu thích nhiều sách |
| R10 | SÁCH | YÊU THÍCH | 1:N | Một sách được nhiều người yêu thích |
| R11 | NGƯỜI DÙNG | ĐÁNH GIÁ | 1:N | Một người dùng có nhiều đánh giá |
| R12 | SÁCH | ĐÁNH GIÁ | 1:N | Một sách có nhiều đánh giá |
| R13 | MÃ GIẢM GIÁ | ĐƠN HÀNG | 1:N | Một mã giảm giá áp dụng nhiều đơn |



---

## 2. SƠ ĐỒ CDM (Conceptual Data Model)

### 2.1 Mô hình khái niệm

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SƠ ĐỒ CDM - MÔ HÌNH KHÁI NIỆM                            │
└─────────────────────────────────────────────────────────────────────────────┘

                              ┌─────────────────┐
                              │    TÁC GIẢ      │
                              │─────────────────│
                              │ • Tên tác giả   │
                              │ • Tiểu sử       │
                              │ • Hình ảnh      │
                              └────────┬────────┘
                                       │
                                       │ viết
                                       │ (1,n)
                                       ▼
┌─────────────────┐           ┌─────────────────┐           ┌─────────────────┐
│   THỂ LOẠI      │           │      SÁCH       │           │  NHÀ XUẤT BẢN   │
│─────────────────│           │─────────────────│           │─────────────────│
│ • Tên thể loại  │◄──────────│ • Tên sách      │──────────►│ • Tên NXB       │
│ • Mô tả         │  thuộc    │ • Mô tả         │  xuất bản │ • Địa chỉ       │
│ • Hình ảnh      │  (n,1)    │ • Giá bán       │  (n,1)    │ • Số điện thoại │
└─────────────────┘           │ • Giá khuyến mãi│           └─────────────────┘
                              │ • Số lượng tồn  │
                              │ • Trạng thái    │
                              └────────┬────────┘
                                       │
              ┌────────────────────────┼────────────────────────┐
              │                        │                        │
              │ có trong               │ được đánh giá          │ được yêu thích
              │ (1,n)                  │ (0,n)                  │ (0,n)
              ▼                        ▼                        ▼
     ┌─────────────────┐      ┌─────────────────┐      ┌─────────────────┐
     │  CHI TIẾT ĐH    │      │    ĐÁNH GIÁ     │      │   YÊU THÍCH     │
     │─────────────────│      │─────────────────│      │─────────────────│
     │ • Số lượng      │      │ • Điểm số       │      │ • Ngày thêm     │
     │ • Giá tại TĐ    │      │ • Nội dung      │      └────────┬────────┘
     └────────┬────────┘      │ • Trạng thái    │               │
              │               └────────┬────────┘               │
              │ thuộc                  │ của                    │ của
              │ (n,1)                  │ (n,1)                  │ (n,1)
              ▼                        ▼                        ▼
     ┌─────────────────┐      ┌─────────────────────────────────┐
     │    ĐƠN HÀNG     │      │          NGƯỜI DÙNG             │
     │─────────────────│      │─────────────────────────────────│
     │ • Mã đơn        │◄─────│ • Họ tên                        │
     │ • Tổng tiền     │ đặt  │ • Email                         │
     │ • Trạng thái    │(1,n) │ • Mật khẩu                      │
     │ • Địa chỉ giao  │      │ • Số điện thoại                 │
     │ • PT thanh toán │      │ • Địa chỉ                       │
     └────────┬────────┘      │ • Vai trò (Admin/Customer)      │
              │               └─────────────────────────────────┘
              │ sử dụng                        │
              │ (n,0..1)                       │ có
              ▼                                │ (1,n)
     ┌─────────────────┐                       ▼
     │  MÃ GIẢM GIÁ    │              ┌─────────────────┐
     │─────────────────│              │    GIỎ HÀNG     │
     │ • Mã code       │              │─────────────────│
     │ • Loại giảm giá │              │ • Số lượng      │
     │ • Giá trị       │              │ • Giá tại TĐ    │
     │ • Ngày hiệu lực │              └─────────────────┘
     │ • Số lượng      │
     └─────────────────┘
```

### 2.2 Danh sách thực thể và thuộc tính

| STT | Thực thể | Thuộc tính chính | Mô tả |
|-----|----------|------------------|-------|
| 1 | NGƯỜI DÙNG | Họ tên, Email, Mật khẩu, SĐT, Địa chỉ, Vai trò | Người sử dụng hệ thống |
| 2 | SÁCH | Tên, Mô tả, Giá, Số lượng, Trạng thái | Sản phẩm bán |
| 3 | TÁC GIẢ | Tên, Tiểu sử, Hình ảnh | Người viết sách |
| 4 | THỂ LOẠI | Tên, Mô tả, Hình ảnh | Phân loại sách |
| 5 | NHÀ XUẤT BẢN | Tên, Địa chỉ, SĐT | Đơn vị xuất bản |
| 6 | ĐƠN HÀNG | Mã đơn, Tổng tiền, Trạng thái, Địa chỉ | Đơn đặt hàng |
| 7 | CHI TIẾT ĐH | Số lượng, Giá tại thời điểm | Chi tiết sản phẩm trong đơn |
| 8 | GIỎ HÀNG | Số lượng, Giá tại thời điểm | Sản phẩm trong giỏ |
| 9 | YÊU THÍCH | Ngày thêm | Sách yêu thích |
| 10 | ĐÁNH GIÁ | Điểm số, Nội dung, Trạng thái | Đánh giá sách |
| 11 | MÃ GIẢM GIÁ | Mã, Loại, Giá trị, Ngày hiệu lực | Khuyến mãi |



---

## 3. SƠ ĐỒ PDM (Physical Data Model)

### 3.1 Cấu trúc bảng vật lý

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SƠ ĐỒ PDM - MÔ HÌNH VẬT LÝ                               │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────┐      ┌─────────────────────────────┐
│         tac_gia             │      │          the_loai           │
├─────────────────────────────┤      ├─────────────────────────────┤
│ PK ma_tac_gia    INT AI     │      │ PK ma_the_loai   INT AI     │
│    ten_tac_gia   VARCHAR(255)│     │    ten_the_loai  VARCHAR(255)│
│    tieu_su       TEXT        │      │    mo_ta         TEXT        │
│    hinh_anh      VARCHAR(255)│      │    hinh_anh      VARCHAR(255)│
│    duong_dan     VARCHAR(255)│      │    duong_dan     VARCHAR(255)│
│    created_at    TIMESTAMP   │      │    created_at    TIMESTAMP   │
│    updated_at    TIMESTAMP   │      │    updated_at    TIMESTAMP   │
│    deleted_at    TIMESTAMP   │      │    deleted_at    TIMESTAMP   │
└──────────────┬──────────────┘      └──────────────┬──────────────┘
               │                                     │
               │ FK                                  │ FK
               ▼                                     ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                                  sach                                        │
├─────────────────────────────────────────────────────────────────────────────┤
│ PK ma_sach           INT AUTO_INCREMENT                                      │
│    ten_sach          VARCHAR(255) NOT NULL                                   │
│    duong_dan         VARCHAR(255) UNIQUE                                     │
│    mo_ta             TEXT                                                    │
│    noi_dung          LONGTEXT                                                │
│    hinh_anh          VARCHAR(255)                                            │
│    gia_ban           DECIMAL(12,0) NOT NULL                                  │
│    gia_khuyen_mai    DECIMAL(12,0) NULL                                      │
│    so_luong_ton      INT DEFAULT 0                                           │
│    nam_xuat_ban      INT                                                     │
│ FK ma_the_loai       INT → the_loai(ma_the_loai)                            │
│ FK ma_tac_gia        INT → tac_gia(ma_tac_gia)                              │
│ FK ma_nxb            INT → nha_xuat_ban(ma_nxb)                             │
│    trang_thai        ENUM('active','inactive') DEFAULT 'active'              │
│    luot_xem          INT DEFAULT 0                                           │
│    diem_trung_binh   DECIMAL(3,2) DEFAULT 0                                  │
│    so_luot_danh_gia  INT DEFAULT 0                                           │
│    created_at        TIMESTAMP                                               │
│    updated_at        TIMESTAMP                                               │
│    deleted_at        TIMESTAMP NULL                                          │
└─────────────────────────────────────────────────────────────────────────────┘
               │
               │ FK
               ▼
┌─────────────────────────────┐      ┌─────────────────────────────┐
│       nha_xuat_ban          │      │           users             │
├─────────────────────────────┤      ├─────────────────────────────┤
│ PK ma_nxb        INT AI     │      │ PK id            INT AI     │
│    ten_nxb       VARCHAR(255)│     │    ho_ten        VARCHAR(255)│
│    dia_chi       TEXT        │      │    email         VARCHAR(255) UNIQUE│
│    so_dien_thoai VARCHAR(20) │      │    mat_khau      VARCHAR(255)│
│    email         VARCHAR(255)│      │    so_dien_thoai VARCHAR(20) │
│    website       VARCHAR(255)│      │    dia_chi       TEXT        │
│    duong_dan     VARCHAR(255)│      │    vai_tro       ENUM('admin','customer')│
│    created_at    TIMESTAMP   │      │    xac_minh_email_luc TIMESTAMP│
│    updated_at    TIMESTAMP   │      │    token_ghi_nho VARCHAR(100)│
│    deleted_at    TIMESTAMP   │      │    created_at    TIMESTAMP   │
└─────────────────────────────┘      │    updated_at    TIMESTAMP   │
                                     │    deleted_at    TIMESTAMP   │
                                     └──────────────┬──────────────┘
                                                    │
                    ┌───────────────────────────────┼───────────────────────────┐
                    │                               │                           │
                    ▼                               ▼                           ▼
┌─────────────────────────────┐  ┌─────────────────────────────┐  ┌─────────────────────────────┐
│         gio_hang            │  │         yeu_thich           │  │         danh_gia            │
├─────────────────────────────┤  ├─────────────────────────────┤  ├─────────────────────────────┤
│ PK ma_gio_hang   INT AI     │  │ PK ma_yeu_thich  INT AI     │  │ PK ma_danh_gia   INT AI     │
│ FK ma_nguoi_dung INT        │  │ FK ma_nguoi_dung INT        │  │ FK ma_nguoi_dung INT        │
│ FK ma_sach       INT        │  │ FK ma_sach       INT        │  │ FK ma_sach       INT        │
│    so_luong      INT        │  │    created_at    TIMESTAMP  │  │    diem_so       INT(1-5)   │
│    gia_tai_thoi_diem DECIMAL│  │    updated_at    TIMESTAMP  │  │    noi_dung      TEXT       │
│    created_at    TIMESTAMP  │  └─────────────────────────────┘  │    trang_thai    ENUM       │
│    updated_at    TIMESTAMP  │                                   │    created_at    TIMESTAMP  │
└─────────────────────────────┘                                   │    updated_at    TIMESTAMP  │
                                                                  └─────────────────────────────┘
```



### 3.2 Bảng đơn hàng và chi tiết

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              don_hang                                        │
├─────────────────────────────────────────────────────────────────────────────┤
│ PK ma_don_hang           INT AUTO_INCREMENT                                  │
│    ma_don                VARCHAR(20) UNIQUE                                  │
│ FK ma_nguoi_dung         INT → users(id)                                    │
│    tong_tien             DECIMAL(15,2) NOT NULL                              │
│    tong_tien_goc         DECIMAL(15,2)                                       │
│    so_tien_giam_gia      DECIMAL(15,2) DEFAULT 0                             │
│ FK ma_giam_gia_id        INT → ma_giam_gia(ma_giam_gia_id) NULL             │
│    trang_thai            ENUM('cho_xac_nhan','da_xac_nhan',                  │
│                               'dang_giao','da_giao','da_huy')                │
│    dia_chi_giao          TEXT NOT NULL                                       │
│    so_dien_thoai_giao    VARCHAR(20) NOT NULL                                │
│    phuong_thuc_thanh_toan ENUM('cod','chuyen_khoan','the_tin_dung')         │
│    ghi_chu               TEXT                                                │
│    created_at            TIMESTAMP                                           │
│    updated_at            TIMESTAMP                                           │
│    deleted_at            TIMESTAMP NULL                                      │
└─────────────────────────────────────────────────────────────────────────────┘
                                     │
                                     │ FK
                                     ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           chi_tiet_don_hang                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│ PK ma_chi_tiet           INT AUTO_INCREMENT                                  │
│ FK ma_don_hang           INT → don_hang(ma_don_hang)                        │
│ FK ma_sach               INT → sach(ma_sach)                                │
│    so_luong              INT NOT NULL                                        │
│    gia_ban_tai_thoi_diem DECIMAL(12,0) NOT NULL                              │
│    created_at            TIMESTAMP                                           │
│    updated_at            TIMESTAMP                                           │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                              ma_giam_gia                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│ PK ma_giam_gia_id        INT AUTO_INCREMENT                                  │
│    ma_code               VARCHAR(50) UNIQUE NOT NULL                         │
│    ten_ma                VARCHAR(255)                                        │
│    mo_ta                 TEXT                                                │
│    loai_giam_gia         ENUM('phan_tram','so_tien') DEFAULT 'phan_tram'    │
│    gia_tri               DECIMAL(15,2) NOT NULL                              │
│    gia_tri_toi_thieu     DECIMAL(15,2) DEFAULT 0                             │
│    giam_toi_da           DECIMAL(15,2) NULL                                  │
│    ngay_bat_dau          DATE NOT NULL                                       │
│    ngay_ket_thuc         DATE NOT NULL                                       │
│    so_luong              INT NULL                                            │
│    so_lan_su_dung        INT DEFAULT 0                                       │
│    trang_thai            ENUM('active','inactive') DEFAULT 'active'          │
│    created_at            TIMESTAMP                                           │
│    updated_at            TIMESTAMP                                           │
│    deleted_at            TIMESTAMP NULL                                      │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.3 Indexes và Constraints

```sql
-- Primary Keys
ALTER TABLE sach ADD PRIMARY KEY (ma_sach);
ALTER TABLE users ADD PRIMARY KEY (id);
ALTER TABLE don_hang ADD PRIMARY KEY (ma_don_hang);
ALTER TABLE chi_tiet_don_hang ADD PRIMARY KEY (ma_chi_tiet);

-- Foreign Keys
ALTER TABLE sach ADD CONSTRAINT fk_sach_the_loai 
    FOREIGN KEY (ma_the_loai) REFERENCES the_loai(ma_the_loai);
ALTER TABLE sach ADD CONSTRAINT fk_sach_tac_gia 
    FOREIGN KEY (ma_tac_gia) REFERENCES tac_gia(ma_tac_gia);
ALTER TABLE sach ADD CONSTRAINT fk_sach_nxb 
    FOREIGN KEY (ma_nxb) REFERENCES nha_xuat_ban(ma_nxb);
ALTER TABLE don_hang ADD CONSTRAINT fk_don_hang_user 
    FOREIGN KEY (ma_nguoi_dung) REFERENCES users(id);
ALTER TABLE chi_tiet_don_hang ADD CONSTRAINT fk_ctdh_don_hang 
    FOREIGN KEY (ma_don_hang) REFERENCES don_hang(ma_don_hang);
ALTER TABLE chi_tiet_don_hang ADD CONSTRAINT fk_ctdh_sach 
    FOREIGN KEY (ma_sach) REFERENCES sach(ma_sach);

-- Indexes
CREATE INDEX idx_sach_the_loai ON sach(ma_the_loai);
CREATE INDEX idx_sach_tac_gia ON sach(ma_tac_gia);
CREATE INDEX idx_sach_trang_thai ON sach(trang_thai);
CREATE INDEX idx_don_hang_user ON don_hang(ma_nguoi_dung);
CREATE INDEX idx_don_hang_trang_thai ON don_hang(trang_thai);
CREATE UNIQUE INDEX idx_users_email ON users(email);
CREATE UNIQUE INDEX idx_sach_duong_dan ON sach(duong_dan);
```



---

## 4. SƠ ĐỒ USE CASE

### 4.1 Use Case tổng quan

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SƠ ĐỒ USE CASE - HỆ THỐNG NHÀ SÁCH                       │
└─────────────────────────────────────────────────────────────────────────────┘

                                    ┌─────────────────────────────────────────┐
                                    │           HỆ THỐNG NHÀ SÁCH             │
                                    │                                         │
     ┌─────────┐                    │  ┌─────────────────────────────────┐   │
     │         │                    │  │      QUẢN LÝ TÀI KHOẢN          │   │
     │  KHÁCH  │───────────────────►│  │  ○ Đăng ký                      │   │
     │  VÃNG   │                    │  │  ○ Đăng nhập                    │   │
     │  LAI    │                    │  │  ○ Quên mật khẩu                │   │
     │         │                    │  └─────────────────────────────────┘   │
     └─────────┘                    │                                         │
          │                         │  ┌─────────────────────────────────┐   │
          │                         │  │      XEM SẢN PHẨM               │   │
          │                         │  │  ○ Xem danh sách sách           │   │
          ├────────────────────────►│  │  ○ Xem chi tiết sách            │   │
          │                         │  │  ○ Tìm kiếm sách                │   │
          │                         │  │  ○ Lọc theo thể loại/tác giả    │   │
          │                         │  └─────────────────────────────────┘   │
          │                         │                                         │
          │                         │  ┌─────────────────────────────────┐   │
     ┌─────────┐                    │  │      GIỎ HÀNG                   │   │
     │         │                    │  │  ○ Thêm vào giỏ hàng            │   │
     │ KHÁCH   │───────────────────►│  │  ○ Cập nhật số lượng            │   │
     │  HÀNG   │                    │  │  ○ Xóa khỏi giỏ hàng            │   │
     │(Customer)│                   │  │  ○ Xem giỏ hàng                 │   │
     │         │                    │  └─────────────────────────────────┘   │
     └─────────┘                    │                                         │
          │                         │  ┌─────────────────────────────────┐   │
          │                         │  │      ĐẶT HÀNG                   │   │
          ├────────────────────────►│  │  ○ Thanh toán                   │   │
          │                         │  │  ○ Áp dụng mã giảm giá          │   │
          │                         │  │  ○ Chọn phương thức thanh toán  │   │
          │                         │  │  ○ Nhập địa chỉ giao hàng       │   │
          │                         │  └─────────────────────────────────┘   │
          │                         │                                         │
          │                         │  ┌─────────────────────────────────┐   │
          │                         │  │      QUẢN LÝ ĐƠN HÀNG           │   │
          ├────────────────────────►│  │  ○ Xem lịch sử đơn hàng         │   │
          │                         │  │  ○ Theo dõi đơn hàng            │   │
          │                         │  │  ○ Hủy đơn hàng                 │   │
          │                         │  └─────────────────────────────────┘   │
          │                         │                                         │
          │                         │  ┌─────────────────────────────────┐   │
          │                         │  │      TƯƠNG TÁC                  │   │
          └────────────────────────►│  │  ○ Đánh giá sách                │   │
                                    │  │  ○ Yêu thích sách               │   │
                                    │  │  ○ Cập nhật thông tin cá nhân   │   │
                                    │  └─────────────────────────────────┘   │
                                    │                                         │
                                    │                                         │
     ┌─────────┐                    │  ┌─────────────────────────────────┐   │
     │         │                    │  │      QUẢN LÝ SÁCH               │   │
     │  ADMIN  │───────────────────►│  │  ○ Thêm sách mới                │   │
     │         │                    │  │  ○ Sửa thông tin sách           │   │
     │         │                    │  │  ○ Xóa sách                     │   │
     │         │                    │  │  ○ Quản lý tồn kho              │   │
     └─────────┘                    │  └─────────────────────────────────┘   │
          │                         │                                         │
          │                         │  ┌─────────────────────────────────┐   │
          │                         │  │      QUẢN LÝ DANH MỤC           │   │
          ├────────────────────────►│  │  ○ Quản lý thể loại             │   │
          │                         │  │  ○ Quản lý tác giả              │   │
          │                         │  │  ○ Quản lý nhà xuất bản         │   │
          │                         │  └─────────────────────────────────┘   │
          │                         │                                         │
          │                         │  ┌─────────────────────────────────┐   │
          │                         │  │      QUẢN LÝ ĐƠN HÀNG           │   │
          ├────────────────────────►│  │  ○ Xem danh sách đơn hàng       │   │
          │                         │  │  ○ Cập nhật trạng thái          │   │
          │                         │  │  ○ In hóa đơn                   │   │
          │                         │  └─────────────────────────────────┘   │
          │                         │                                         │
          │                         │  ┌─────────────────────────────────┐   │
          │                         │  │      QUẢN LÝ NGƯỜI DÙNG         │   │
          ├────────────────────────►│  │  ○ Xem danh sách người dùng     │   │
          │                         │  │  ○ Khóa/Mở khóa tài khoản       │   │
          │                         │  │  ○ Phân quyền                   │   │
          │                         │  └─────────────────────────────────┘   │
          │                         │                                         │
          │                         │  ┌─────────────────────────────────┐   │
          │                         │  │      QUẢN LÝ KHUYẾN MÃI         │   │
          ├────────────────────────►│  │  ○ Tạo mã giảm giá              │   │
          │                         │  │  ○ Sửa/Xóa mã giảm giá          │   │
          │                         │  │  ○ Thống kê sử dụng             │   │
          │                         │  └─────────────────────────────────┘   │
          │                         │                                         │
          │                         │  ┌─────────────────────────────────┐   │
          └────────────────────────►│  │      BÁO CÁO THỐNG KÊ           │   │
                                    │  │  ○ Thống kê doanh thu           │   │
                                    │  │  ○ Sách bán chạy                │   │
                                    │  │  ○ Báo cáo tồn kho              │   │
                                    │  └─────────────────────────────────┘   │
                                    │                                         │
                                    └─────────────────────────────────────────┘
```



### 4.2 Mô tả chi tiết Use Case

#### UC01: Đăng ký tài khoản
| Thuộc tính | Mô tả |
|------------|-------|
| Actor | Khách vãng lai |
| Mô tả | Người dùng tạo tài khoản mới |
| Tiền điều kiện | Chưa có tài khoản |
| Luồng chính | 1. Nhập họ tên, email, mật khẩu, SĐT<br>2. Hệ thống validate<br>3. Tạo tài khoản<br>4. Tự động đăng nhập |
| Luồng phụ | Email đã tồn tại → Thông báo lỗi |
| Hậu điều kiện | Tài khoản được tạo |

#### UC02: Đăng nhập
| Thuộc tính | Mô tả |
|------------|-------|
| Actor | Khách hàng, Admin |
| Mô tả | Đăng nhập vào hệ thống |
| Tiền điều kiện | Có tài khoản |
| Luồng chính | 1. Nhập email, mật khẩu<br>2. Hệ thống xác thực<br>3. Chuyển hướng theo vai trò |
| Luồng phụ | Sai thông tin → Thông báo lỗi |

#### UC03: Tìm kiếm sách
| Thuộc tính | Mô tả |
|------------|-------|
| Actor | Tất cả người dùng |
| Mô tả | Tìm kiếm sách theo từ khóa |
| Tiền điều kiện | Không |
| Luồng chính | 1. Nhập từ khóa<br>2. Chọn bộ lọc (thể loại, giá, tác giả)<br>3. Hiển thị kết quả<br>4. Sắp xếp kết quả |

#### UC04: Thêm vào giỏ hàng
| Thuộc tính | Mô tả |
|------------|-------|
| Actor | Khách hàng |
| Mô tả | Thêm sách vào giỏ hàng |
| Tiền điều kiện | Sách còn hàng |
| Luồng chính | 1. Chọn sách<br>2. Chọn số lượng<br>3. Nhấn "Thêm vào giỏ"<br>4. Cập nhật giỏ hàng |
| Luồng phụ | Hết hàng → Thông báo |

#### UC05: Đặt hàng
| Thuộc tính | Mô tả |
|------------|-------|
| Actor | Khách hàng |
| Mô tả | Hoàn tất đơn hàng |
| Tiền điều kiện | Có sản phẩm trong giỏ, đã đăng nhập |
| Luồng chính | 1. Xem giỏ hàng<br>2. Nhập địa chỉ giao<br>3. Chọn PT thanh toán<br>4. Áp dụng mã giảm giá (nếu có)<br>5. Xác nhận đặt hàng |
| Hậu điều kiện | Đơn hàng được tạo, trừ tồn kho |

#### UC06: Quản lý sách (Admin)
| Thuộc tính | Mô tả |
|------------|-------|
| Actor | Admin |
| Mô tả | CRUD sách |
| Tiền điều kiện | Đăng nhập với quyền Admin |
| Luồng chính | 1. Xem danh sách sách<br>2. Thêm/Sửa/Xóa sách<br>3. Upload ảnh bìa<br>4. Cập nhật tồn kho |

#### UC07: Cập nhật trạng thái đơn hàng (Admin)
| Thuộc tính | Mô tả |
|------------|-------|
| Actor | Admin |
| Mô tả | Cập nhật trạng thái đơn hàng |
| Tiền điều kiện | Đơn hàng tồn tại |
| Luồng chính | 1. Xem chi tiết đơn<br>2. Chọn trạng thái mới<br>3. Xác nhận<br>4. Gửi email thông báo |



---

## 5. SƠ ĐỒ PHÂN CẤP CHỨC NĂNG

### 5.1 Sơ đồ phân cấp tổng quan

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SƠ ĐỒ PHÂN CẤP CHỨC NĂNG - NHÀ SÁCH                      │
└─────────────────────────────────────────────────────────────────────────────┘

                            ┌─────────────────────┐
                            │   HỆ THỐNG BÁN      │
                            │   SÁCH ONLINE       │
                            └──────────┬──────────┘
                                       │
        ┌──────────────────────────────┼──────────────────────────────┐
        │                              │                              │
        ▼                              ▼                              ▼
┌───────────────────┐      ┌───────────────────┐      ┌───────────────────┐
│  QUẢN LÝ NGƯỜI    │      │   QUẢN LÝ BÁN     │      │   QUẢN LÝ HỆ      │
│      DÙNG         │      │      HÀNG         │      │     THỐNG         │
└────────┬──────────┘      └────────┬──────────┘      └────────┬──────────┘
         │                          │                          │
    ┌────┴────┐              ┌──────┴──────┐            ┌──────┴──────┐
    │         │              │             │            │             │
    ▼         ▼              ▼             ▼            ▼             ▼
┌───────┐ ┌───────┐    ┌─────────┐ ┌─────────┐   ┌─────────┐ ┌─────────┐
│Đăng ký│ │Đăng   │    │Quản lý  │ │Quản lý  │   │Quản lý  │ │Báo cáo  │
│tài    │ │nhập   │    │sản phẩm │ │đơn hàng │   │danh mục │ │thống kê │
│khoản  │ │       │    │         │ │         │   │         │ │         │
└───────┘ └───────┘    └────┬────┘ └────┬────┘   └────┬────┘ └────┬────┘
                            │           │             │           │
                       ┌────┴────┐ ┌────┴────┐   ┌────┴────┐ ┌────┴────┐
                       │         │ │         │   │         │ │         │
                       ▼         ▼ ▼         ▼   ▼         ▼ ▼         ▼
                    ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐ ┌─────┐
                    │Sách │ │Giỏ  │ │Tạo  │ │Cập  │ │Thể  │ │Doanh│ │Tồn  │
                    │     │ │hàng │ │đơn  │ │nhật │ │loại │ │thu  │ │kho  │
                    └─────┘ └─────┘ └─────┘ └─────┘ └─────┘ └─────┘ └─────┘
```

### 5.2 Chi tiết phân cấp chức năng

```
HỆ THỐNG BÁN SÁCH ONLINE
│
├── 1. QUẢN LÝ NGƯỜI DÙNG
│   │
│   ├── 1.1 Đăng ký tài khoản
│   │   ├── 1.1.1 Nhập thông tin cá nhân
│   │   ├── 1.1.2 Xác thực email
│   │   └── 1.1.3 Tạo tài khoản
│   │
│   ├── 1.2 Đăng nhập/Đăng xuất
│   │   ├── 1.2.1 Đăng nhập bằng email
│   │   ├── 1.2.2 Ghi nhớ đăng nhập
│   │   └── 1.2.3 Đăng xuất
│   │
│   ├── 1.3 Quên mật khẩu
│   │   ├── 1.3.1 Gửi email reset
│   │   └── 1.3.2 Đặt mật khẩu mới
│   │
│   ├── 1.4 Quản lý thông tin cá nhân
│   │   ├── 1.4.1 Xem thông tin
│   │   ├── 1.4.2 Cập nhật thông tin
│   │   └── 1.4.3 Đổi mật khẩu
│   │
│   └── 1.5 Quản lý người dùng (Admin)
│       ├── 1.5.1 Xem danh sách
│       ├── 1.5.2 Khóa/Mở khóa
│       └── 1.5.3 Phân quyền
│
├── 2. QUẢN LÝ SẢN PHẨM
│   │
│   ├── 2.1 Quản lý sách
│   │   ├── 2.1.1 Xem danh sách sách
│   │   ├── 2.1.2 Thêm sách mới
│   │   ├── 2.1.3 Sửa thông tin sách
│   │   ├── 2.1.4 Xóa sách
│   │   ├── 2.1.5 Upload ảnh bìa
│   │   └── 2.1.6 Quản lý tồn kho
│   │
│   ├── 2.2 Quản lý thể loại
│   │   ├── 2.2.1 Xem danh sách
│   │   ├── 2.2.2 Thêm thể loại
│   │   ├── 2.2.3 Sửa thể loại
│   │   └── 2.2.4 Xóa thể loại
│   │
│   ├── 2.3 Quản lý tác giả
│   │   ├── 2.3.1 Xem danh sách
│   │   ├── 2.3.2 Thêm tác giả
│   │   ├── 2.3.3 Sửa tác giả
│   │   └── 2.3.4 Xóa tác giả
│   │
│   └── 2.4 Quản lý nhà xuất bản
│       ├── 2.4.1 Xem danh sách
│       ├── 2.4.2 Thêm NXB
│       ├── 2.4.3 Sửa NXB
│       └── 2.4.4 Xóa NXB
│
├── 3. QUẢN LÝ BÁN HÀNG
│   │
│   ├── 3.1 Giỏ hàng
│   │   ├── 3.1.1 Xem giỏ hàng
│   │   ├── 3.1.2 Thêm sản phẩm
│   │   ├── 3.1.3 Cập nhật số lượng
│   │   ├── 3.1.4 Xóa sản phẩm
│   │   └── 3.1.5 Xóa toàn bộ giỏ
│   │
│   ├── 3.2 Đặt hàng
│   │   ├── 3.2.1 Nhập địa chỉ giao
│   │   ├── 3.2.2 Chọn PT thanh toán
│   │   ├── 3.2.3 Áp dụng mã giảm giá
│   │   └── 3.2.4 Xác nhận đơn hàng
│   │
│   ├── 3.3 Quản lý đơn hàng (Khách)
│   │   ├── 3.3.1 Xem lịch sử đơn
│   │   ├── 3.3.2 Xem chi tiết đơn
│   │   ├── 3.3.3 Theo dõi đơn hàng
│   │   └── 3.3.4 Hủy đơn hàng
│   │
│   ├── 3.4 Quản lý đơn hàng (Admin)
│   │   ├── 3.4.1 Xem tất cả đơn
│   │   ├── 3.4.2 Lọc theo trạng thái
│   │   ├── 3.4.3 Cập nhật trạng thái
│   │   └── 3.4.4 In hóa đơn
│   │
│   └── 3.5 Quản lý mã giảm giá
│       ├── 3.5.1 Tạo mã giảm giá
│       ├── 3.5.2 Sửa mã giảm giá
│       ├── 3.5.3 Xóa mã giảm giá
│       └── 3.5.4 Thống kê sử dụng
│
├── 4. TƯƠNG TÁC NGƯỜI DÙNG
│   │
│   ├── 4.1 Tìm kiếm
│   │   ├── 4.1.1 Tìm theo từ khóa
│   │   ├── 4.1.2 Lọc theo thể loại
│   │   ├── 4.1.3 Lọc theo giá
│   │   ├── 4.1.4 Lọc theo tác giả
│   │   └── 4.1.5 Sắp xếp kết quả
│   │
│   ├── 4.2 Yêu thích
│   │   ├── 4.2.1 Thêm yêu thích
│   │   ├── 4.2.2 Xóa yêu thích
│   │   └── 4.2.3 Xem DS yêu thích
│   │
│   └── 4.3 Đánh giá
│       ├── 4.3.1 Viết đánh giá
│       ├── 4.3.2 Chấm điểm
│       └── 4.3.3 Xem đánh giá
│
└── 5. BÁO CÁO THỐNG KÊ
    │
    ├── 5.1 Dashboard
    │   ├── 5.1.1 Tổng quan doanh thu
    │   ├── 5.1.2 Đơn hàng mới
    │   ├── 5.1.3 Sách bán chạy
    │   └── 5.1.4 Cảnh báo tồn kho
    │
    ├── 5.2 Báo cáo doanh thu
    │   ├── 5.2.1 Theo ngày/tuần/tháng
    │   ├── 5.2.2 Biểu đồ doanh thu
    │   └── 5.2.3 So sánh kỳ trước
    │
    └── 5.3 Báo cáo sản phẩm
        ├── 5.3.1 Sách bán chạy
        ├── 5.3.2 Sách tồn kho thấp
        └── 5.3.3 Sách không bán được
```



### 5.3 Ma trận chức năng - Actor

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    MA TRẬN CHỨC NĂNG - ACTOR                                │
└─────────────────────────────────────────────────────────────────────────────┘

┌────────────────────────────────┬─────────┬─────────┬─────────┐
│         CHỨC NĂNG              │  KHÁCH  │ KHÁCH   │  ADMIN  │
│                                │ VÃNG LAI│  HÀNG   │         │
├────────────────────────────────┼─────────┼─────────┼─────────┤
│ Xem trang chủ                  │    ✓    │    ✓    │    ✓    │
│ Xem danh sách sách             │    ✓    │    ✓    │    ✓    │
│ Xem chi tiết sách              │    ✓    │    ✓    │    ✓    │
│ Tìm kiếm sách                  │    ✓    │    ✓    │    ✓    │
│ Lọc sách theo thể loại         │    ✓    │    ✓    │    ✓    │
├────────────────────────────────┼─────────┼─────────┼─────────┤
│ Đăng ký tài khoản              │    ✓    │    -    │    -    │
│ Đăng nhập                      │    ✓    │    ✓    │    ✓    │
│ Đăng xuất                      │    -    │    ✓    │    ✓    │
│ Quên mật khẩu                  │    ✓    │    ✓    │    ✓    │
├────────────────────────────────┼─────────┼─────────┼─────────┤
│ Thêm vào giỏ hàng              │    ✓*   │    ✓    │    ✓    │
│ Xem giỏ hàng                   │    ✓*   │    ✓    │    ✓    │
│ Cập nhật giỏ hàng              │    ✓*   │    ✓    │    ✓    │
│ Xóa khỏi giỏ hàng              │    ✓*   │    ✓    │    ✓    │
├────────────────────────────────┼─────────┼─────────┼─────────┤
│ Đặt hàng                       │    -    │    ✓    │    -    │
│ Xem lịch sử đơn hàng           │    -    │    ✓    │    -    │
│ Theo dõi đơn hàng              │    -    │    ✓    │    -    │
│ Hủy đơn hàng                   │    -    │    ✓    │    -    │
├────────────────────────────────┼─────────┼─────────┼─────────┤
│ Yêu thích sách                 │    -    │    ✓    │    -    │
│ Đánh giá sách                  │    -    │    ✓    │    -    │
│ Cập nhật thông tin cá nhân     │    -    │    ✓    │    ✓    │
├────────────────────────────────┼─────────┼─────────┼─────────┤
│ Quản lý sách (CRUD)            │    -    │    -    │    ✓    │
│ Quản lý thể loại               │    -    │    -    │    ✓    │
│ Quản lý tác giả                │    -    │    -    │    ✓    │
│ Quản lý nhà xuất bản           │    -    │    -    │    ✓    │
│ Quản lý đơn hàng               │    -    │    -    │    ✓    │
│ Quản lý người dùng             │    -    │    -    │    ✓    │
│ Quản lý mã giảm giá            │    -    │    -    │    ✓    │
│ Xem báo cáo thống kê           │    -    │    -    │    ✓    │
│ Dashboard admin                │    -    │    -    │    ✓    │
└────────────────────────────────┴─────────┴─────────┴─────────┘

Chú thích:
✓  : Có quyền truy cập
-  : Không có quyền
✓* : Giỏ hàng lưu trong session (không lưu database)
```

---

## 6. SƠ ĐỒ LUỒNG DỮ LIỆU (DFD)

### 6.1 DFD Level 0 (Context Diagram)

```
                              ┌─────────────────────┐
                              │                     │
        Thông tin đăng ký     │                     │     Thông tin sách
    ─────────────────────────►│                     │◄────────────────────
        Thông tin đăng nhập   │                     │     Thông tin đơn
    ─────────────────────────►│    HỆ THỐNG        │◄────────────────────
                              │    BÁN SÁCH        │
┌─────────┐   Đơn hàng        │    ONLINE          │        ┌─────────┐
│ KHÁCH   │──────────────────►│                     │◄───────│  ADMIN  │
│  HÀNG   │◄──────────────────│                     │───────►│         │
└─────────┘   Xác nhận đơn    │                     │        └─────────┘
              Thông báo       │                     │   Báo cáo
                              │                     │   Thống kê
                              └─────────────────────┘
```

### 6.2 DFD Level 1

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           DFD LEVEL 1                                        │
└─────────────────────────────────────────────────────────────────────────────┘

                    ┌───────────────┐
                    │   D1: SÁCH    │
                    └───────┬───────┘
                            │
        ┌───────────────────┼───────────────────┐
        │                   │                   │
        ▼                   ▼                   ▼
┌───────────────┐   ┌───────────────┐   ┌───────────────┐
│  1.0 QUẢN LÝ  │   │  2.0 QUẢN LÝ  │   │  3.0 QUẢN LÝ  │
│   SẢN PHẨM    │   │   GIỎ HÀNG    │   │   ĐƠN HÀNG    │
└───────┬───────┘   └───────┬───────┘   └───────┬───────┘
        │                   │                   │
        │                   │                   │
        ▼                   ▼                   ▼
┌───────────────┐   ┌───────────────┐   ┌───────────────┐
│ D2: THỂ LOẠI  │   │ D3: GIỎ HÀNG  │   │ D4: ĐƠN HÀNG  │
│ D5: TÁC GIẢ   │   └───────────────┘   │ D6: CHI TIẾT  │
│ D7: NXB       │                       └───────────────┘
└───────────────┘

        ┌───────────────────────────────────────┐
        │                                       │
        ▼                                       ▼
┌───────────────┐                       ┌───────────────┐
│  4.0 QUẢN LÝ  │                       │  5.0 BÁO CÁO  │
│  NGƯỜI DÙNG   │                       │   THỐNG KÊ    │
└───────┬───────┘                       └───────────────┘
        │
        ▼
┌───────────────┐
│ D8: NGƯỜI DÙNG│
└───────────────┘
```

---

## 7. TÓM TẮT

### Các thực thể chính:
1. **NGƯỜI DÙNG (users)** - Quản lý tài khoản admin và khách hàng
2. **SÁCH (sach)** - Sản phẩm chính của hệ thống
3. **THỂ LOẠI (the_loai)** - Phân loại sách
4. **TÁC GIẢ (tac_gia)** - Thông tin tác giả
5. **NHÀ XUẤT BẢN (nha_xuat_ban)** - Đơn vị xuất bản
6. **ĐƠN HÀNG (don_hang)** - Đơn đặt hàng
7. **CHI TIẾT ĐƠN HÀNG (chi_tiet_don_hang)** - Chi tiết sản phẩm trong đơn
8. **GIỎ HÀNG (gio_hang)** - Giỏ hàng tạm
9. **YÊU THÍCH (yeu_thich)** - Danh sách yêu thích
10. **ĐÁNH GIÁ (danh_gia)** - Đánh giá sách
11. **MÃ GIẢM GIÁ (ma_giam_gia)** - Khuyến mãi

### Các Actor:
1. **Khách vãng lai** - Xem sách, tìm kiếm, đăng ký
2. **Khách hàng** - Mua hàng, đánh giá, yêu thích
3. **Admin** - Quản lý toàn bộ hệ thống

### Công nghệ:
- **Backend**: Laravel 10.x, PHP 8.1+
- **Database**: SQLite/MySQL
- **Frontend**: Blade, Bootstrap 5, JavaScript
- **ORM**: Eloquent
