<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modify karyawans table
        Schema::table('karyawans', function (Blueprint $table) {
            $table->string('password')->nullable()->after('foto');
            $table->string('divisi')->nullable()->after('jabatan');
            $table->decimal('gaji_per_periode', 15, 2)->default(0)->after('divisi');
            $table->date('last_medical_checkup')->nullable()->after('gaji_per_periode');
            $table->string('nomor_str')->nullable()->after('last_medical_checkup'); // for AhliGizi
        });

        // 2. Create purchase_orders table
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_po')->unique();
            $table->date('tanggal_po');
            $table->date('tanggal_pengiriman')->nullable();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->string('status')->default('draft'); // draft, dikirim, selesai, dibatalkan
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->timestamps();
        });

        // 3. Create detail_pos table
        Schema::create('detail_pos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->onDelete('cascade');
            $table->decimal('kuantitas_pesan', 15, 2);
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('kuantitas_diterima', 15, 2)->default(0);
            $table->timestamps();
        });

        // 4. Create pembayarans table
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('metode_bayar'); // transfer, tunai, dll
            $table->string('status_bayar'); // lunas, pending, gagal
            $table->string('bukti_transfer')->nullable();
            $table->timestamps();
        });

        // 5. Create penerimaan_barangs table
        Schema::create('penerimaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->date('tanggal_terima');
            $table->string('kondisi_bahan'); // baik, rusak, dll
            $table->decimal('kuantitas_rijek', 15, 2)->default(0);
            $table->boolean('status_rijek')->default(false);
            $table->timestamps();
        });

        // 6. Create stok_gudangs table
        Schema::create('stok_gudangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->onDelete('cascade');
            $table->date('tanggal_masuk');
            $table->decimal('kuantitas', 15, 2);
            $table->string('lokasi_gudang')->nullable();
            $table->string('metode_fifo')->default('FIFO');
            $table->timestamps();
        });

        // 7. Create karyawan_menus table
        Schema::create('karyawan_menus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
            $table->date('tanggal');
            $table->string('jenis_porsi');
            $table->integer('total_pm');
            $table->string('dibuat_oleh'); // nama atau ID Karyawan/AhliGizi
            $table->string('status')->default('draft'); // draft, published
            $table->timestamps();
        });

        // 8. Create gramasi_menus table
        Schema::create('gramasi_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('karyawan_menus')->onDelete('cascade');
            $table->foreignId('bahan_baku_id')->constrained('bahan_baku')->onDelete('cascade');
            $table->decimal('gramasi_bersih', 15, 2);
            $table->decimal('gramasi_kotor', 15, 2);
            $table->timestamps();
        });

        // 9. Create kebutuhan_harians table
        Schema::create('kebutuhan_harians', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('total_pm');
            $table->decimal('buffer_persen', 5, 2);
            $table->decimal('budget_harian', 15, 2);
            $table->timestamps();
        });

        // 10. Create budgets table
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('jenis_budget'); // harian, mingguan, dll
            $table->decimal('total_budget', 15, 2);
            $table->decimal('total_realisasi', 15, 2)->default(0);
            $table->decimal('sisa', 15, 2);
            $table->timestamps();
        });

        // 11. Create absensis table
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->date('tanggal');
            $table->dateTime('waktu_masuk')->nullable();
            $table->dateTime('waktu_keluar')->nullable();
            $table->string('status_hadir'); // hadir, absen, izin, sakit
            $table->timestamps();
        });

        // 12. Create penggajians table
        Schema::create('penggajians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->string('periode'); // YYYY-MM
            $table->integer('jumlah_hari_kerja');
            $table->decimal('total_gaji', 15, 2);
            $table->string('status_bayar')->default('belum_dibayar'); // belum_dibayar, dibayar
            $table->date('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajians');
        Schema::dropIfExists('absensis');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('kebutuhan_harians');
        Schema::dropIfExists('gramasi_menus');
        Schema::dropIfExists('karyawan_menus');
        Schema::dropIfExists('stok_gudangs');
        Schema::dropIfExists('penerimaan_barangs');
        Schema::dropIfExists('pembayarans');
        Schema::dropIfExists('detail_pos');
        Schema::dropIfExists('purchase_orders');
        
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropColumn([
                'password',
                'divisi',
                'gaji_per_periode',
                'last_medical_checkup',
                'nomor_str'
            ]);
        });
    }
};
