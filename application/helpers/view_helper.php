<?php

function namaPendek($namaLengkap) {
    $nama = explode(' ', $namaLengkap);
    if (sizeof($nama) > 3) {
        $nama_p = $nama[0]." ".$nama[1]." ".$nama[2];
    } else {
        $nama_p = $namaLengkap;
    }
    
    return $nama_p;
}

function namaPanggilan($namaLengkap) {
    $nama = explode(' ', $namaLengkap);
    if (sizeof($nama) > 1) {
        if ($nama[0] == 'M' || $nama[0] == 'M.' || $nama[0] == 'Muhamad' || $nama[0] == 'Muhammad' || $nama[0] == 'Mohamed' || $nama[0] == 'Mohammed' || $nama[0] == 'Moch' || $nama[0] == 'Moch.' || $nama[0] == 'Mochamad' || $nama[0] == 'Mochammad' || $nama[0] == 'Muh' || $nama[0] == 'Muh.' || $nama[0] == 'Much' || $nama[0] == 'Much.' || $nama[0] == 'Muchamad' || $nama[0] == 'Muchammad') {
            $panggilan = $nama[1];
        } else {
            $panggilan = $nama[0];
        }
    } else {
        $panggilan = $namaLengkap;
    }
    
    return $panggilan;
}

function bulanIndonesia() {
    $bulan = date('m');
    switch ($bulan) {
        case '01':
            $bulan = 'Januari';
            break;
        case '02':
            $bulan = 'Februari';
            break;
        case '03':
            $bulan = 'Maret';
            break;
        case '04':
            $bulan = 'April';
            break;
        case '05':
            $bulan = 'Mei';
            break;
        case '06':
            $bulan = 'Juni';
            break;
        case '07':
            $bulan = 'Juli';
            break;
        case '08':
            $bulan = 'Agustus';
            break;
        case '09':
            $bulan = 'September';
            break;
        case '10':
            $bulan = 'Oktober';
            break;
        case '11':
            $bulan = 'November';
            break;
        case '12':
            $bulan = 'Desember';
            break;
        default:
            $bulan = date('F');
            break;
    }

    return $bulan;
}

function pesanGrup($kanal, $userId) {
    $ci =& get_instance();
    $lastMsg = $ci->db->order_by('pesan_id DESC')->limit(1)->where('pesan_kanal', $kanal)->get('t_pesan')->row();
    $textMsg = "";
    $statusMsg = 0;
    $hideMsg = "";
    if ($lastMsg != null) {
        $statusMsg = $ci->M_database->countWhere('t_pesan_status', array('pesan_pengirim' => $kanal, 'pesan_penerima' => "asisten_$userId"));
        if ($lastMsg->pesan_user != "asisten_$userId-asisten" && $statusMsg > 0) {
            $textMsg = "$lastMsg->pesan_isi";
            $hideMsg = "";
            $styleMsg = 'style="font-weight:600"';
        } else {
            $textMsg = "$lastMsg->pesan_isi";
            $hideMsg = "hide";
            $styleMsg = 'style="font-weight:400"';
        }                        
    } else {
        $textMsg = 'Tidak ada pesan';
        $hideMsg = "hide";
        $styleMsg = 'style="font-weight:400"';
    }

    $data = [];

    $result = explode(',', "$statusMsg, $textMsg, $hideMsg, $styleMsg");
    $data['unRead'] = $result[0];
    $data['pesanText'] = $result[1];
    $data['pesanStatus'] = $result[2];
    $data['styleText'] = $result[3];

    return $data;
}

function pesanPribadi($userId, $asistenId) {
    $ci =& get_instance();
    $lastMsg = $ci->db->order_by('pesan_id DESC')->limit(1)->where('pesan_user', "asisten_$asistenId-asisten_$userId")->or_where('pesan_user', "asisten_$userId-asisten_$asistenId")->get('t_pesan')->row();
    $textMsg = "";
    $statusMsg = 0;
    $hideMsg = "";
    if ($lastMsg != null) {
        $statusMsg = $ci->M_database->countWhere('t_pesan_status', array('pesan_pengirim' => "asisten_$asistenId", 'pesan_penerima' => "asisten_$userId"));
        $readMsg = $ci->M_database->countWhere('t_pesan_status', array('pesan_id' => $lastMsg->pesan_id, 'pesan_pengirim' => "asisten_$userId", 'pesan_penerima' => "asisten_$asistenId"));
        if ($lastMsg->pesan_user == "asisten_$asistenId-asisten_$userId" && $statusMsg > 0) {
            $textMsg = "$lastMsg->pesan_isi";
            $hideMsg = "";
            $styleMsg = 'style="font-weight:600"';
        } else {
            $textMsg = $lastMsg->pesan_isi;
            $hideMsg = "hide";
            $styleMsg = 'style="font-weight:400"';
        }

        if ($lastMsg->pesan_user != "asisten_$userId-asisten_$asistenId") {
            $isRead = '';
        } else {
            if ($readMsg > 0) {                                
                $isRead ='<i class="material-icons right grey-text" style="font-size:20px; margin:0 8px 0 0">done_all</i>';
            } else {
                $isRead ='<i class="material-icons right light-blue-text text-lighten-1" style="font-size:20px; margin:0 8px 0 0">done_all</i>';
            }
        }                                         
    } else {
        $textMsg = 'Tidak ada pesan';
        $hideMsg = "hide";
        $isRead = "";
        $styleMsg = 'style="font-weight:400"';
    }

    $data = [];

    $result = explode(',', "$statusMsg, $textMsg, $hideMsg, $styleMsg, $isRead");
    $data['unRead'] = $result[0];
    $data['pesanText'] = $result[1];
    $data['pesanStatus'] = $result[2];
    $data['styleText'] = $result[3];
    $data['isRead'] = $result[4];

    return $data;
}

function pesanGrupDosen($kanal, $userId) {
    $ci =& get_instance();
    $lastMsg = $ci->db->order_by('pesan_id DESC')->limit(1)->where('pesan_kanal', $kanal)->get('t_pesan')->row();
    $textMsg = "";
    $statusMsg = 0;
    $hideMsg = "";
    if ($lastMsg != null) {
        $statusMsg = $ci->M_database->countWhere('t_pesan_status', array('pesan_pengirim' => $kanal, 'pesan_penerima' => "dosen_$userId"));
        if ($lastMsg->pesan_user != "dosen_$userId-asisten" && $statusMsg > 0) {
            $textMsg = "$lastMsg->pesan_isi";
            $hideMsg = "";
            $styleMsg = 'style="font-weight:600"';
        } else {
            $textMsg = "$lastMsg->pesan_isi";
            $hideMsg = "hide";
            $styleMsg = 'style="font-weight:400"';
        }                        
    } else {
        $textMsg = 'Tidak ada pesan';
        $hideMsg = "hide";
        $styleMsg = 'style="font-weight:400"';
    }

    $data = [];

    $result = explode(',', "$statusMsg, $textMsg, $hideMsg, $styleMsg");
    $data['unRead'] = $result[0];
    $data['pesanText'] = $result[1];
    $data['pesanStatus'] = $result[2];
    $data['styleText'] = $result[3];

    return $data;
}

function pesanPribadiDosen($userId, $asistenId) {
    $ci =& get_instance();
    $lastMsg = $ci->db->order_by('pesan_id DESC')->limit(1)->where('pesan_user', "asisten_$asistenId-dosen_$userId")->or_where('pesan_user', "dosen_$userId-asisten_$asistenId")->get('t_pesan')->row();
    $textMsg = "";
    $statusMsg = 0;
    $hideMsg = "";
    if ($lastMsg != null) {
        $statusMsg = $ci->M_database->countWhere('t_pesan_status', array('pesan_pengirim' => "asisten_$asistenId", 'pesan_penerima' => "dosen_$userId"));
        $readMsg = $ci->M_database->countWhere('t_pesan_status', array('pesan_id' => $lastMsg->pesan_id, 'pesan_pengirim' => "dosen_$userId", 'pesan_penerima' => "asisten_$asistenId"));
        if ($lastMsg->pesan_user == "asisten_$asistenId-dosen_$userId" && $statusMsg > 0) {
            $textMsg = "$lastMsg->pesan_isi";
            $hideMsg = "";
            $styleMsg = 'style="font-weight:600"';
        } else {
            $textMsg = $lastMsg->pesan_isi;
            $hideMsg = "hide";
            $styleMsg = 'style="font-weight:400"';
        }

        if ($lastMsg->pesan_user != "dosen_$userId-asisten_$asistenId") {
            $isRead = '';
        } else {
            if ($readMsg > 0) {                                
                $isRead ='<i class="material-icons right grey-text" style="font-size:20px; margin:0 8px 0 0">done_all</i>';
            } else {
                $isRead ='<i class="material-icons right light-blue-text text-lighten-1" style="font-size:20px; margin:0 8px 0 0">done_all</i>';
            }
        }                                         
    } else {
        $textMsg = 'Tidak ada pesan';
        $hideMsg = "hide";
        $isRead = "";
        $styleMsg = 'style="font-weight:400"';
    }

    $data = [];

    $result = explode(',', "$statusMsg, $textMsg, $hideMsg, $styleMsg, $isRead");
    $data['unRead'] = $result[0];
    $data['pesanText'] = $result[1];
    $data['pesanStatus'] = $result[2];
    $data['styleText'] = $result[3];
    $data['isRead'] = $result[4];

    return $data;
}

?>