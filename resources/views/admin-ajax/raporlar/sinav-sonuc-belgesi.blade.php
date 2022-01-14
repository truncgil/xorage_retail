<?php $logo = "assets/img/logo.svg" ?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KONU ANALİZLİ SINAV SONUÇ; BELGESİ</title>
</head>

<body>
    <table border="0" cellpadding="1" cellspacing="1" style="width:100%">
        <tbody>
            <tr>
                <td style="text-align:center"><img src="{{$logo}}" alt=""></td>
                <td style="text-align:center">
                    <h1>KONU ANALİZLİ SINAV SONUÇ; BELGESİ</h1>
                </td>
            </tr>
        </tbody>
    </table>

    <p>&nbsp;</p>

    <p>{dersler}</p>

    <table border="0" cellpadding="1" cellspacing="1" class="table table-bordered table-striped" style="width:100%">
        <tbody>
            <tr>
                <td><strong>&Ouml;ğrenci Adı</strong></td>
                <td>{ogrenci_adi}</td>
                <td><strong>Numara</strong></td>
                <td>{id}</td>
                <td><strong>Şube</strong></td>
                <td>{sube}</td>
                <td><strong>Okul</strong></td>
                <td colspan="3" rowspan="1">{okul}</td>
            </tr>
            <tr>
                <td><strong>Sınav Adı</strong></td>
                <td>{sinav_adi}</td>
                <td><strong>Alan</strong></td>
                <td>{alan}</td>
                <td><strong>Sınav Tarihi</strong></td>
                <td>{sinav_tarih}</td>
                <td><strong>Kitap&ccedil;ık</strong></td>
                <td>{kitapcik}</td>
                <td><strong>Danışman</strong></td>
                <td>{danisman}</td>
            </tr>
            <tr>
                <td rowspan="2" style="text-align:center"><strong>DERSLER</strong></td>
                <td rowspan="2" style="text-align:center"><strong>Soru Sayısı</strong></td>
                <td rowspan="2" style="text-align:center"><strong>Doğru</strong></td>
                <td rowspan="2" style="text-align:center"><strong>Yanlış</strong></td>
                <td rowspan="2" style="text-align:center"><strong>Net</strong></td>
                <td rowspan="2" style="text-align:center"><strong>Başarı %</strong></td>
                <td colspan="4" rowspan="2" style="text-align:center"><strong>Cevaplar</strong></td>
            </tr>
        </tbody>
    </table>

    <p>{dersler_table}</p>

    <table border="0" cellpadding="1" cellspacing="1" style="width:100%">
        <tbody>
            <tr>
                <td rowspan="4" style="text-align:center">TYT<br />
                    {tyt_puan}</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">Şube</td>
                <td style="text-align:center">Okul</td>
                <td rowspan="4" style="text-align:center">YKS-EA</td>
                <td rowspan="1" style="text-align:center">&nbsp;</td>
                <td rowspan="1" style="text-align:center">Şube</td>
                <td rowspan="1" style="text-align:center">Okul</td>
            </tr>
            <tr>
                <td style="text-align:center">Katılımcı Sayısı</td>
                <td style="text-align:center">{tyt_sube_katilimci}</td>
                <td style="text-align:center">{tyt_okul_katilimci}</td>
                <td style="text-align:center">Katılımcı Sayısı</td>
                <td style="text-align:center">{yks_sube_katilimci}</td>
                <td style="text-align:center">{yks_okul_katilimci}</td>
            </tr>
            <tr>
                <td style="text-align:center">Ortalama</td>
                <td style="text-align:center">{tyt_sube_ortalama}</td>
                <td style="text-align:center">{tyt_okul_ortalama}</td>
                <td style="text-align:center">Ortalama</td>
                <td style="text-align:center">{yks_sube_ortalama}</td>
                <td style="text-align:center">{yks_okul_ortalama}</td>
            </tr>
            <tr>
                <td style="text-align:center">Derece</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">Derece</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <table border="0" cellpadding="1" cellspacing="1" style="width:100%">
        <tbody>
            <tr>
                <td rowspan="4" style="text-align:center">YKS-SAY<br />
                    {tyt_puan}</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">Şube</td>
                <td style="text-align:center">Okul</td>
                <td rowspan="4" style="text-align:center">YKS-S&Ouml;Z</td>
                <td rowspan="1" style="text-align:center">&nbsp;</td>
                <td rowspan="1" style="text-align:center">Şube</td>
                <td rowspan="1" style="text-align:center">Okul</td>
            </tr>
            <tr>
                <td style="text-align:center">Katılımcı Sayısı</td>
                <td style="text-align:center">{yks_say_sube_katilimci}</td>
                <td style="text-align:center">{yks_say_okul_katilimci}</td>
                <td style="text-align:center">Katılımcı Sayısı</td>
                <td style="text-align:center">{yks_say_sube_katilimci}</td>
                <td style="text-align:center">{yks_say_okul_katilimci}</td>
            </tr>
            <tr>
                <td style="text-align:center">Ortalama</td>
                <td style="text-align:center">{yks_say_sube_ortalama}</td>
                <td style="text-align:center">{yks_say_okul_ortalama}</td>
                <td style="text-align:center">Ortalama</td>
                <td style="text-align:center">{yks_say_sube_ortalama}</td>
                <td style="text-align:center">{yks_say_okul_ortalama}</td>
            </tr>
            <tr>
                <td style="text-align:center">Derece</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">Derece</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <table border="0" cellpadding="1" cellspacing="1" style="width:100%">
        <tbody>
            <tr>
                <td style="text-align:center">Konu Adı</td>
                <td style="text-align:center; width:30px">Sayı</td>
                <td style="text-align:center; width:30px">Doğ</td>
                <td style="text-align:center; width:30px">Yan</td>
                <td style="text-align:center; width:30px">Boş</td>
                <td style="text-align:center; width:30px">%</td>
                <td style="text-align:center">Konu Adı</td>
                <td style="text-align:center; width:30px">Sayı</td>
                <td style="text-align:center; width:30px">Doğ</td>
                <td style="text-align:center; width:30px">Yan</td>
                <td style="text-align:center; width:30px">Boş</td>
                <td style="text-align:center; width:30px">%</td>
            </tr>
            <tr>
                <td style="text-align:center">{konular_sag}</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">{konular_sol}</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
                <td style="text-align:center">&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <p>&nbsp;</p>

</body>

</html>