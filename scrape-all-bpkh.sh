#!/bin/bash

# Batch script untuk scrape semua BPKH files (Linux/Mac)
# Cara pakai: chmod +x scrape-all-bpkh.sh && ./scrape-all-bpkh.sh

echo "========================================"
echo "BPKH File Scraper - Batch Mode"
echo "========================================"
echo ""

# Cek apakah user ingin scrape semua atau satu-satu
echo "Pilihan:"
echo "1. Scrape SEMUA BPKH sekaligus (cepat, tapi tidak bisa monitor per BPKH)"
echo "2. Scrape satu-satu (lambat, tapi bisa monitor progress detail)"
echo "3. Test dengan 1 BPKH saja (ID=1)"
echo "4. Preview data BPKH (tanpa download)"
echo ""

read -p "Pilih opsi (1/2/3/4): " choice

case $choice in
    1)
        echo ""
        echo "Memulai scraping SEMUA BPKH..."
        echo ""
        php artisan bpkh:scrape-files --all
        ;;
    2)
        echo ""
        echo "Scraping satu-satu..."
        echo "Masukkan ID BPKH yang ingin di-scrape (pisahkan dengan spasi)"
        echo "Contoh: 1 2 3 4 5"
        echo ""
        read -p "ID BPKH: " ids

        for id in $ids; do
            echo ""
            echo "========================================"
            echo "Scraping BPKH ID: $id"
            echo "========================================"
            php artisan bpkh:scrape-files --id=$id
        done
        ;;
    3)
        echo ""
        echo "Testing dengan BPKH ID=1..."
        echo ""
        php artisan bpkh:scrape-files --id=1
        ;;
    4)
        echo ""
        echo "Menampilkan preview data BPKH..."
        echo ""
        php test-bpkh-scraper.php
        ;;
    *)
        echo ""
        echo "Pilihan tidak valid!"
        ;;
esac

echo ""
echo "========================================"
echo "Selesai!"
echo "========================================"
echo ""
echo "File tersimpan di: storage/app/private/scrapping_script/bpkh_form/"
echo ""
