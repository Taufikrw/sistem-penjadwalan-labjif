<?php

namespace App\Enums;

enum Course: string
{
    case PENDIDIKAN_AGAMA_ISLAM = 'Pendidikan Agama Islam';
    case PENDIDIKAN_PANCASILA = 'Pendidikan Pancasila';
    case PENDIDIKAN_KEWARGANEGARAAN = 'Pendidikan Kewarganegaraan';
    case BELA_NEGARA_DAN_WIDYA_MWAT_YASA = 'Bela Negara dan Widya Mwat Yasa';
    case OLAHRAGA_1 = 'Olahraga 1';
    case OLAHRAGA_2 = 'Olahraga 2';
    case BAHASA_INDONESIA = 'Bahasa Indonesia';
    case BAHASA_INGGRIS = 'Bahasa Inggris';
    case TECHNOPRENEURSHIP = 'Technopreneurship';
    case KONSEP_TEKNOLOGI = 'Konsep Teknologi';
    case KALKULUS = 'Kalkulus';
    case PENGANTAR_SISTEM_INFORMASI = 'Pengantar Sistem Informasi';
    case MATEMATIKA_DISKRIT = 'Matematika Diskrit';
    case DASAR_DASAR_PEMROGRAMAN = 'Dasar Dasar Pemrograman';
    case MANAJEMEN_BASIS_DATA = 'Manajemen Basis Data';
    case PRAKTIKUM_DASAR_DASAR_PEMROGRAMAN = 'Praktikum Dasar Dasar Pemrograman';
    case PRAKTIKUM_MANAJEMEN_BASIS_DATA = 'Praktikum Manajemen Basis Data';
    case ALGORITMA_PEMROGRAMAN_DAN_STRUKTUR_DATA = 'Algoritma Pemrograman dan Struktur Data';
    case PEMROGRAMAN_WEB_DASAR = 'Pemrograman Web Dasar';
    case MANAJEMEN_DAN_ORGANISASI = 'Manajemen dan Organisasi';
    case PENGANTAR_METODE_STATISTIKA = 'Pengantar Metode Statistika';
    case PENGANTAR_BISNIS = 'Pengantar Bisnis';
    case PRAKTIKUM_ALGORITMA_PEMROGRAMAN_DAN_STRUKTUR_DATA = 'Praktikum Algoritma Pemrograman dan Struktur Data';
    case PRAKTIKUM_PEMROGRAMAN_WEB_DASAR = 'Praktikum Pemrograman Web Dasar';
    case PERENCANAAN_STRATEGIS_TI = 'Perencanaan Strategis TI';
    case REKAYASA_KEBUTUHAN_PERANGKAT_LUNAK = 'Rekayasa Kebutuhan Perangkat Lunak';
    case SISTEM_PENDUKUNG_KEPUTUSAN = 'Sistem Pendukung Keputusan';
    case KEAMANAN_ASET_INFORMASI = 'Keamanan Aset Informasi';
    case DESAIN_DAN_MANAJEMEN_JARINGAN_KOMPUTER = 'Desain dan Manajemen Jaringan Komputer';
    case METODE_SURVEY_DAN_PENGOLAHAN_DATA = 'Metode Survey dan Pengolahan Data';
    case INTERAKSI_MANUSIA_DAN_COMPUTER = 'Interaksi Manusia dan Computer';
    case KAPITA_SELEKTA = 'Kapita Selekta';

    public function prodi(): string
    {
        return match ($this) {
            self::PENDIDIKAN_AGAMA_ISLAM => 'Sistem Informasi',
            self::PENDIDIKAN_PANCASILA => 'Sistem Informasi',
            self::PENDIDIKAN_KEWARGANEGARAAN => 'Sistem Informasi',
            self::BELA_NEGARA_DAN_WIDYA_MWAT_YASA => 'Sistem Informasi',
            self::OLAHRAGA_1 => 'Teknik Informatika',
            self::OLAHRAGA_2 => 'Teknik Informatika',
            self::BAHASA_INDONESIA => 'Sistem Informasi',
            self::BAHASA_INGGRIS => 'Sistem Informasi',
            self::TECHNOPRENEURSHIP => 'Sistem Informasi',
            self::KONSEP_TEKNOLOGI => 'Sistem Informasi',
            self::KALKULUS => 'Sistem Informasi',
            self::PENGANTAR_SISTEM_INFORMASI => 'Sistem Informasi',
            self::MATEMATIKA_DISKRIT => 'Sistem Informasi',
            self::DASAR_DASAR_PEMROGRAMAN => 'Sistem Informasi',
            self::MANAJEMEN_BASIS_DATA => 'Sistem Informasi',
            self::PRAKTIKUM_DASAR_DASAR_PEMROGRAMAN => 'Sistem Informasi',
            self::PRAKTIKUM_MANAJEMEN_BASIS_DATA => 'Sistem Informasi',
            self::ALGORITMA_PEMROGRAMAN_DAN_STRUKTUR_DATA => 'Sistem Informasi',
            self::PEMROGRAMAN_WEB_DASAR => 'Sistem Informasi',
            self::MANAJEMEN_DAN_ORGANISASI => 'Sistem Informasi',
            self::PENGANTAR_METODE_STATISTIKA => 'Sistem Informasi',
            self::PENGANTAR_BISNIS => 'Sistem Informasi',
            self::PRAKTIKUM_ALGORITMA_PEMROGRAMAN_DAN_STRUKTUR_DATA => 'Sistem Informasi',
            self::PRAKTIKUM_PEMROGRAMAN_WEB_DASAR => 'Sistem Informasi',
            self::PERENCANAAN_STRATEGIS_TI => 'Sistem Informasi',
            self::REKAYASA_KEBUTUHAN_PERANGKAT_LUNAK => 'Sistem Informasi',
            self::SISTEM_PENDUKUNG_KEPUTUSAN => 'Sistem Informasi',
            self::KEAMANAN_ASET_INFORMASI => 'Sistem Informasi',
            self::DESAIN_DAN_MANAJEMEN_JARINGAN_KOMPUTER => 'Sistem Informasi',
            self::METODE_SURVEY_DAN_PENGOLAHAN_DATA => 'Sistem Informasi',
            self::INTERAKSI_MANUSIA_DAN_COMPUTER => 'Sistem Informasi',
            self::KAPITA_SELEKTA => 'Sistem Informasi',
        };
    }
}
