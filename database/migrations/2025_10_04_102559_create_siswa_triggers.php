<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Trigger insert untuk data_kelas
        DB::unprepared('
            CREATE TRIGGER after_insert_siswa_kelas
            AFTER INSERT ON data_siswa
            FOR EACH ROW
            UPDATE data_kelas
            SET jumlah_siswa = jumlah_siswa + 1
            WHERE id = NEW.kelas_id
        ');

        // Trigger insert untuk program_kejuruan
        DB::unprepared('
            CREATE TRIGGER after_insert_siswa_kejuruan
            AFTER INSERT ON data_siswa
            FOR EACH ROW
            UPDATE program_kejuruan
            SET jumlah_siswa = jumlah_siswa + 1
            WHERE id = NEW.kejuruan_id
        ');

        // Trigger delete untuk data_kelas (aman)
        DB::unprepared('
            CREATE TRIGGER after_delete_siswa_kelas
            AFTER DELETE ON data_siswa
            FOR EACH ROW
            UPDATE data_kelas
            SET jumlah_siswa = CASE
                WHEN jumlah_siswa > 0 THEN jumlah_siswa - 1
                ELSE 0
            END
            WHERE id = OLD.kelas_id
        ');

        // Trigger delete untuk program_kejuruan (aman)
        DB::unprepared('
            CREATE TRIGGER after_delete_siswa_kejuruan
            AFTER DELETE ON data_siswa
            FOR EACH ROW
            UPDATE program_kejuruan
            SET jumlah_siswa = CASE
                WHEN jumlah_siswa > 0 THEN jumlah_siswa - 1
                ELSE 0
            END
            WHERE id = OLD.kejuruan_id
        ');

        // Trigger update untuk kelas_id (pengurangan aman)
        DB::unprepared('
            CREATE TRIGGER after_update_siswa_kelas
            AFTER UPDATE ON data_siswa
            FOR EACH ROW
            UPDATE data_kelas
            SET jumlah_siswa = CASE
                WHEN jumlah_siswa > 0 THEN jumlah_siswa - 1
                ELSE 0
            END
            WHERE id = OLD.kelas_id AND OLD.kelas_id != NEW.kelas_id
        ');

        // Trigger update untuk kelas_id (penambahan)
        DB::unprepared('
            CREATE TRIGGER after_update_siswa_kelas_add
            AFTER UPDATE ON data_siswa
            FOR EACH ROW
            UPDATE data_kelas
            SET jumlah_siswa = jumlah_siswa + 1
            WHERE id = NEW.kelas_id AND OLD.kelas_id != NEW.kelas_id
        ');

        // Trigger update untuk kejuruan_id (pengurangan aman)
        DB::unprepared('
            CREATE TRIGGER after_update_siswa_kejuruan
            AFTER UPDATE ON data_siswa
            FOR EACH ROW
            UPDATE program_kejuruan
            SET jumlah_siswa = CASE
                WHEN jumlah_siswa > 0 THEN jumlah_siswa - 1
                ELSE 0
            END
            WHERE id = OLD.kejuruan_id AND OLD.kejuruan_id != NEW.kejuruan_id
        ');

        // Trigger update untuk kejuruan_id (penambahan)
        DB::unprepared('
            CREATE TRIGGER after_update_siswa_kejuruan_add
            AFTER UPDATE ON data_siswa
            FOR EACH ROW
            UPDATE program_kejuruan
            SET jumlah_siswa = jumlah_siswa + 1
            WHERE id = NEW.kejuruan_id AND OLD.kejuruan_id != NEW.kejuruan_id
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_siswa_kelas');
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_siswa_kejuruan');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_siswa_kelas');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_siswa_kejuruan');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_siswa_kelas');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_siswa_kelas_add');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_siswa_kejuruan');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_siswa_kejuruan_add');
    }
};
