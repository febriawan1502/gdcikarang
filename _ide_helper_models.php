<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int|null $unit_id
 * @property string $hari
 * @property string $tanggal
 * @property string $tanggal_teks
 * @property string $mengetahui
 * @property string $jabatan_mengetahui
 * @property string $pembuat
 * @property string $jabatan_pembuat
 * @property string|null $file_pdf
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara whereFilePdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara whereHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara whereJabatanMengetahui($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara whereJabatanPembuat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara whereMengetahui($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara wherePembuat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara whereTanggalTeks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BeritaAcara whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBeritaAcara {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $nomor
 * @property string|null $company_code
 * @property string|null $company_code_description
 * @property string|null $plant
 * @property string|null $plant_description
 * @property string|null $storage_location
 * @property string|null $storage_location_description
 * @property string|null $rak
 * @property string|null $material_type
 * @property string|null $material_type_description
 * @property string|null $material_code
 * @property string|null $material_description
 * @property string|null $material_group
 * @property string|null $base_unit_of_measure
 * @property string|null $valuation_type
 * @property numeric $unrestricted_use_stock
 * @property numeric $quality_inspection_stock
 * @property numeric $blocked_stock
 * @property numeric $in_transit_stock
 * @property numeric $project_stock
 * @property string|null $wbs_element
 * @property string|null $valuation_class
 * @property string|null $valuation_description
 * @property numeric $harga_satuan
 * @property numeric $total_harga
 * @property string $currency
 * @property string|null $pabrikan
 * @property string|null $normalisasi
 * @property int $qty
 * @property int $min_stock
 * @property \Illuminate\Support\Carbon|null $tanggal_terima
 * @property string|null $keterangan
 * @property string $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\User|null $creator
 * @property-read mixed $formatted_harga_satuan
 * @property-read mixed $formatted_tanggal_terima
 * @property-read mixed $formatted_total_harga
 * @property-read mixed $status_badge_color
 * @property-read mixed $total_stock
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialHistory> $histories
 * @property-read int|null $histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialStock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material byPabrikan($pabrikan)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material lowStock($threshold = 10)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material search($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereBaseUnitOfMeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereBlockedStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCompanyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCompanyCodeDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereHargaSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereInTransitStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereMaterialCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereMaterialDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereMaterialGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereMaterialType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereMaterialTypeDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereMinStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereNormalisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material wherePabrikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material wherePlant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material wherePlantDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereProjectStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereQualityInspectionStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereRak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereStorageLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereStorageLocationDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereTanggalTerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereTotalHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereUnrestrictedUseStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereValuationClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereValuationDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereValuationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereWbsElement($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMaterial {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $unit_id
 * @property int $material_id
 * @property string $source_type
 * @property int $source_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $tipe
 * @property string|null $no_slip
 * @property int $masuk
 * @property int $keluar
 * @property int $sisa_persediaan
 * @property string|null $catatan
 * @property int|null $surat_jalan_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $pekerjaan
 * @property-read \App\Models\Material $material
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $source
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereNoSlip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereSisaPersediaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereSuratJalanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMaterialHistory {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $unit_id
 * @property string|null $sumber_material
 * @property string|null $nomor_kr
 * @property string|null $pabrikan
 * @property \Illuminate\Support\Carbon $tanggal_masuk
 * @property string|null $keterangan
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $tanggal_keluar
 * @property string|null $jenis
 * @property string|null $nomor_po
 * @property string|null $nomor_doc
 * @property string|null $tugas_4
 * @property string $status_sap
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialMasukDetail> $details
 * @property-read int|null $details_count
 * @property-read \App\Models\Material|null $material
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk byDateRange($startDate, $endDate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk byMaterial($materialId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereNomorDoc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereNomorKr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereNomorPo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk wherePabrikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereStatusSap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereSumberMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereTanggalKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereTanggalMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereTugas4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasuk whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMaterialMasuk {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $unit_id
 * @property int $material_masuk_id
 * @property int $material_id
 * @property int $quantity
 * @property string $satuan
 * @property string|null $normalisasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Material $material
 * @property-read \App\Models\MaterialMasuk $materialMasuk
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail whereMaterialMasukId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail whereNormalisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialMasukDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMaterialMasukDetail {}
}

namespace App\Models{
/**
 * @property int $id
 * @property numeric $standby
 * @property numeric $garansi
 * @property numeric $perbaikan
 * @property numeric $usul_hapus
 * @property numeric $saldo_awal_tahun
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $total_inspeksi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSavingConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSavingConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSavingConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSavingConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSavingConfig whereGaransi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSavingConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSavingConfig wherePerbaikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSavingConfig whereSaldoAwalTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSavingConfig whereStandby($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSavingConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialSavingConfig whereUsulHapus($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMaterialSavingConfig {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $material_id
 * @property int $unit_id
 * @property numeric $unrestricted_use_stock
 * @property numeric $quality_inspection_stock
 * @property numeric $blocked_stock
 * @property numeric $in_transit_stock
 * @property numeric $project_stock
 * @property int $qty
 * @property int $min_stock
 * @property string|null $rak
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Material $material
 * @property-read \App\Models\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereBlockedStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereInTransitStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereMinStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereProjectStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereQualityInspectionStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereRak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereUnrestrictedUseStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaterialStock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMaterialStock {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $unit_id
 * @property string $unit
 * @property numeric|null $target_harian
 * @property numeric|null $lm1
 * @property numeric|null $lm2
 * @property numeric|null $lm3
 * @property numeric|null $realisasi_harian
 * @property numeric|null $penerimaan_material
 * @property numeric|null $target_persediaan
 * @property numeric|null $realisasi_persediaan
 * @property numeric|null $saldo_sebelumnya
 * @property numeric|null $target_pemakaian
 * @property numeric|null $realisasi_pemakaian
 * @property numeric|null $persen_pemakaian
 * @property numeric|null $persen_pencapaian
 * @property numeric|null $target_jansept
 * @property numeric|null $realisasi_jansept
 * @property numeric|null $ito
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereIto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereLm1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereLm2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereLm3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring wherePenerimaanMaterial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring wherePersenPemakaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring wherePersenPencapaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereRealisasiHarian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereRealisasiJansept($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereRealisasiPemakaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereRealisasiPersediaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereSaldoSebelumnya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereTargetHarian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereTargetJansept($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereTargetPemakaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereTargetPersediaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monitoring whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMonitoring {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $unit_id
 * @property int $material_id
 * @property string $bulan
 * @property int|null $sap
 * @property int|null $fisik
 * @property int|null $sn_mims
 * @property int|null $selisih_sf
 * @property int|null $selisih_ss
 * @property int|null $selisih_fs
 * @property string|null $justifikasi_sf
 * @property string|null $justifikasi_ss
 * @property string|null $justifikasi_fs
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Material $material
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereFisik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereJustifikasiFs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereJustifikasiSf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereJustifikasiSs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereSap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereSelisihFs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereSelisihSf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereSelisihSs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereSnMims($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PemeriksaanFisik whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPemeriksaanFisik {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $unit_id
 * @property int $surat_jalan_detail_id
 * @property string $nomor_surat_masuk
 * @property string $tanggal_masuk
 * @property int $jumlah_kembali
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SuratJalanDetail $detail
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory whereJumlahKembali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory whereNomorSuratMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory whereSuratJalanDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory whereTanggalMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengembalianHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPengembalianHistory {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string $type
 * @property string $group
 * @property string $label
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereValue($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSetting {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $unit_id
 * @property int $material_id
 * @property string $material_description
 * @property numeric $stock_fisik
 * @property numeric $stock_system
 * @property numeric $selisih
 * @property string|null $keterangan
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $createdBy
 * @property-read \App\Models\Material $material
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereMaterialDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereSelisih($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereStockFisik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereStockSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStockOpname {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $unit_id
 * @property string $nomor_surat
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $kepada
 * @property string|null $nama_penerima
 * @property string|null $berdasarkan
 * @property string|null $keterangan
 * @property string|null $nomor_slip
 * @property string|null $kendaraan
 * @property string|null $no_polisi
 * @property string|null $pengemudi
 * @property string|null $foto_penerima
 * @property string $status
 * @property int $created_by
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property string|null $security_checked_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $security
 * @property string $jenis_surat_jalan
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SuratJalanDetail> $details
 * @property-read int|null $details_count
 * @property-read string $status_sap
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaterialHistory> $histories
 * @property-read int|null $histories_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereBerdasarkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereFotoPenerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereJenisSuratJalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereKendaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereKepada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereNamaPenerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereNoPolisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereNomorSlip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereNomorSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan wherePengemudi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereSecurity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereSecurityCheckedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSuratJalan {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $unit_id
 * @property int $surat_jalan_id
 * @property int|null $material_id
 * @property int $is_manual
 * @property string|null $nama_barang_manual
 * @property string|null $satuan_manual
 * @property int $quantity
 * @property int|null $jumlah_kembali
 * @property string|null $tanggal_kembali
 * @property string $satuan
 * @property string|null $keterangan
 * @property int $is_checked
 * @property int|null $checked_by
 * @property string|null $checked_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Material|null $material
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PengembalianHistory> $pengembalianHistories
 * @property-read int|null $pengembalian_histories_count
 * @property-read \App\Models\SuratJalan $suratJalan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereCheckedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereCheckedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereIsChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereIsManual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereJumlahKembali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereNamaBarangManual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereSatuanManual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereSuratJalanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereTanggalKembali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SuratJalanDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSuratJalanDetail {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $plant
 * @property string $storage_location
 * @property string $kode_surat
 * @property int $is_induk
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereIsInduk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereKodeSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit wherePlant($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereStorageLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUnit {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $unit_id
 * @property string $nama
 * @property string|null $nip
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read User|null $creator
 * @property-read mixed $display_name
 * @property-read mixed $initials
 * @property-read mixed $role_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Unit|null $unit
 * @property-read User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User byRole($role)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

