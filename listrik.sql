-- =========================================================
-- DATABASE APLIKASI LISTRIK
-- =========================================================

CREATE DATABASE IF NOT EXISTS listrik
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE listrik;


-- =========================================================
-- 1. TABEL ADMIN
-- =========================================================

CREATE TABLE IF NOT EXISTS admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    pass VARCHAR(255) NOT NULL
) ENGINE=InnoDB;


-- =========================================================
-- 2. TABEL KONSUMEN / USER
-- =========================================================

CREATE TABLE IF NOT EXISTS konsumen (
    id_cust INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telp VARCHAR(20) DEFAULT NULL,
    alamat TEXT DEFAULT NULL,
    pass VARCHAR(255) NOT NULL
) ENGINE=InnoDB;


-- =========================================================
-- 3. TABEL TAGIHAN
-- =========================================================

CREATE TABLE IF NOT EXISTS tagihan (
    id_tagih INT AUTO_INCREMENT PRIMARY KEY,

    email VARCHAR(100) NOT NULL,

    jumlah_pakai INT NOT NULL DEFAULT 0,

    periode VARCHAR(50) NOT NULL,

    harga DECIMAL(15,2) NOT NULL DEFAULT 0,

    deadline DATE DEFAULT NULL,

    pembayaran ENUM('Belum Lunas', 'Lunas')
        NOT NULL DEFAULT 'Belum Lunas',

    CONSTRAINT fk_tagihan_konsumen
        FOREIGN KEY (email)
        REFERENCES konsumen(email)
        ON UPDATE CASCADE
        ON DELETE CASCADE

) ENGINE=InnoDB;


-- =========================================================
-- 4. TABEL FEEDBACK
-- =========================================================

CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,

    email VARCHAR(100) NOT NULL,

    pesan TEXT NOT NULL,

    waktu TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_feedback_konsumen
        FOREIGN KEY (email)
        REFERENCES konsumen(email)
        ON UPDATE CASCADE
        ON DELETE CASCADE

) ENGINE=InnoDB;


-- =========================================================
-- 5. DATA ADMIN DEFAULT
-- =========================================================

INSERT INTO admin (
    username,
    pass
)
SELECT
    'admin',
    'admin123'
WHERE NOT EXISTS (
    SELECT 1
    FROM admin
    WHERE username = 'admin'
);


-- =========================================================
-- 6. DATA USER / KONSUMEN CONTOH
-- =========================================================

INSERT INTO konsumen (
    nama,
    email,
    telp,
    alamat,
    pass
)
SELECT
    'Budi Santoso',
    'budi@gmail.com',
    '081234567890',
    'Surabaya',
    '123456'
WHERE NOT EXISTS (
    SELECT 1
    FROM konsumen
    WHERE email = 'budi@gmail.com'
);


-- =========================================================
-- 7. DATA TAGIHAN CONTOH
-- =========================================================

INSERT INTO tagihan (
    email,
    jumlah_pakai,
    periode,
    harga,
    deadline,
    pembayaran
)
SELECT
    'budi@gmail.com',
    120,
    'Januari 2026',
    150000,
    '2026-02-10',
    'Belum Lunas'
WHERE NOT EXISTS (
    SELECT 1
    FROM tagihan
    WHERE email = 'budi@gmail.com'
      AND periode = 'Januari 2026'
);


-- =========================================================
-- 8. DATA FEEDBACK CONTOH
-- =========================================================

INSERT INTO feedback (
    email,
    pesan
)
SELECT
    'budi@gmail.com',
    'Website cukup mudah digunakan.'
WHERE NOT EXISTS (
    SELECT 1
    FROM feedback
    WHERE email = 'budi@gmail.com'
      AND pesan = 'Website cukup mudah digunakan.'
);


-- =========================================================
-- 9. CEK DATA
-- =========================================================

SELECT * FROM admin;

SELECT * FROM konsumen;

SELECT * FROM tagihan;

SELECT * FROM feedback;