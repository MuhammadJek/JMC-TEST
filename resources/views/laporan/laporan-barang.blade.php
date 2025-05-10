<style type="text/css">
    .table-border-top th,
    .table-border-top td {
        padding: 6px 4px;
        font-size: 11px;
    }

    .table-border-top {
        border-collapse: collapse;

    }

    .table-border-top th {
        vertical-align: middle;
    }

    .table-border-top th {
        border-top: 1px solid black;
        border-bottom: 3px solid black;
        border-right: 1px solid black;
        border-left: 1px solid black;


    }

    .table-header-top td {
        font-size: 16px;

    }

    .td-bottom {
        border-bottom: 1px solid black !important;
        border-right: 1px solid black;
        border-left: 1px solid black;
    }
</style>
<page backtop="5mm" backbottom="5mm" backleft="1mm" backright="1mm" style="font-size: 10pt;">
    <page_footer>
        <table class="page_footer" style="font-size:9pt; text-align: center;">
            <tr>
                <td style="width:350px;">
                    EXPORT PDF LAPORAN . Hal : [[page_cu]] / [[page_nb]]
                </td>

                <td style="width:250px; text-align: right;">
                    Dicetak : {{ date('d/m/Y H:i:s') }}
                </td>
            </tr>
        </table>
    </page_footer>

    <h4 align="center">LAPORAN BARANG</h4>

    <table class="table-border-top">
        <tr>
            <th class="td-bottom" width="90">NO</th>
            <th class="td-bottom" width="90">NAMA BARANG</th>
            <th class="td-bottom" width="90">HARGA BARANG</th>
            <th class="td-bottom" width="90">JUMLAH BARANG</th>
            <th class="td-bottom" width="90">TOTAL BARANG</th>
            <th class="td-bottom" width="90">EXPIRED BARANG</th>
            <th class="td-bottom" width="90">STATUS</th>

        </tr>
        @foreach ($barang as $index => $barangs)
            <tr>
                <td class="td-bottom">{{ $index + 1 }}</td>
                <td class="td-bottom">{{ $barangs->name }}</td>
                <td class="td-bottom">{{ $barangs->harga }}</td>
                <td class="td-bottom">{{ $barangs->jumlah_barang }} {{ $barangs->satuan }}</td>
                <td class="td-bottom">{{ $barangs->total_barang }}</td>
                <td class="td-bottom">{{ $barangs->expired }}</td>
                <td class="td-bottom">{{ $barangs->status }}</td>

            </tr>
        @endforeach

    </table>
    <h4 align="center">INFORMASI UMUM</h4>

    <table class="table-border-top ">
        <tr>
            <th class="td-bottom" width="100">NO</th>
            <th class="td-bottom" width="100">NAMA OPERATOR</th>
            <th class="td-bottom" width="100">KATEGORI BARANG</th>
            <th class="td-bottom" width="100">SUB CATEGORY BARANG</th>
            <th class="td-bottom" width="100">BATAS HARGA</th>
            <th class="td-bottom" width="100">ASAL BARANG</th>
        </tr>
        <tr>
            <td class="td-bottom">{{ 1 }}</td>
            <td class="td-bottom">{{ $informasiUmum->users->name }}</td>
            <td class="td-bottom">{{ $informasiUmum->category->name }}</td>
            <td class="td-bottom">{{ $informasiUmum->subCategory->name }}</td>
            <td class="td-bottom">{{ $informasiUmum->max_price }}</td>
            <td class="td-bottom">{{ $informasiUmum->asal_barang }}</td>
        </tr>
    </table>
</page>
