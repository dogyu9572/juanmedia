<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrBoardList = selectBoardPrint($_REQUEST["boardid"], $_REQUEST["idx"]);
$arrLevel = getArticleList ( $_conf_tbl ["member_level"], 0, 0, "order by level_no desc " );
for($i = 0; $i < $arrLevel["total"]; $i ++) {
    $arrayLevel[$arrLevel["list"][$i]['level_no']] = $arrLevel["list"][$i]['level_name'];
}

//	DB해제
//	SetDisConn($dblink);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>상세내용 프린트</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        h1, h2, h3, h4, h5 {
            page-break-after: avoid;
            color: #333;
        }
        table, figure, .sector {
            page-break-inside: avoid;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
        }

        .table th, .table td {
            padding: .75rem;
            vertical-align: top;
            border: 1px solid #ddd;
            font-size: 12px;
            width: 100%; /* Ensure cells take up full width */
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }

        .table td:first-child {
            font-weight: bold;
            width: 40%;
            max-width: 40%;
            background-color: #f9f9f9;
        }

        .table td:last-child {
            vertical-align: middle;
            font-size: 13px;
            max-width: 100%;
        }
        .sector {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            background-color: #fff;
        }
        .custom-table {
            width: 100%;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            table-layout: fixed; /* Ensure fixed table layout */
        }

        .custom-table th, .custom-table td {
            padding: .75rem;
            vertical-align: top;
            border: 1px solid #ddd;
            font-size: 12px;
        }

        .custom-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }

        .custom-table td:first-child {
            width: 70%; /* Set width for first column */
        }

        .custom-table td:last-child {
            width: 30%; /* Set width for last column */
        }
    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script type="text/javascript">
        $(window).on('load', function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        });
    </script>
</head>
<h1>상영회 신청 내역</h1>
<body>
<?
if($arrBoardList["total"] > 0){
    for($i=0; $i < $arrBoardList["total"]; $i++){
        // 유입경로 처리
        $referralArray = explode('|', $arrBoardList["list"][$i]['referral']);
        $referralText = implode(', ', $referralArray);
        if(in_array('기타', $referralArray) && !empty($arrBoardList["list"][$i]['referral_other'])) {
            $referralText .= ' (' . $arrBoardList["list"][$i]['referral_other'] . ')';
        }
        ?>
        <div class="sector">
            <table class="table">
                <tbody>
                <tr>
                    <td>회원상태</td>
                    <td>
                        <?php

                        //$user_level = $arrList["list"][0]["user_level"];
                        $user_level = $arrBoardList["list"][0]['user_level'];
                        if (isset($arrayLevel[$user_level])) {
                            $level = $arrayLevel[$user_level];
                            echo "<div id='userLevelContainer'>$level</div>";
                        }
                        ?></td>
                </tr>
                <tr>
                <tr>
                    <td>이메일(아이디)</td>
                    <td><?=$arrBoardList["list"][$i]["email"]?></td>
                </tr>
                <tr>
                    <td>이름</td>
                    <td><?=$arrBoardList["list"][$i]["name"]?></td>
                </tr>
                <tr>
                    <td>연락처</td>
                    <td><?=$arrBoardList["list"][$i]["tel"]?></td>
                </tr>
                <tr>
                    <td>생년월일</td>
                    <td><?=$arrBoardList["list"][$i]["birthdate"]?></td>
                </tr>
                <tr>
                    <td>상영회명</td>
                    <td><?=$arrBoardList["list"][$i]["subject"]?></td>
                </tr>
                <tr>
                    <td>신청상태</td>
                    <td><?=$arrBoardList["list"][$i]["status"]?></td>
                </tr>
                <tr>
                    <td>비고</td>
                    <td><?=$arrBoardList["list"][$i]["contents"]?></td>
                </tr>
                <tr>
                    <td>유입경로</td>
                    <td><?=$referralText?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <?
    }
}
?>
</body>
</html>