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

// Fetch instructor names using getBoardListBaseNFile
$eduInstructorsList = getBoardListBaseNFile('edu_instructors', '37', $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset']);
$instructorNames = [];
foreach ($eduInstructorsList["list"] as $instructor) {
    $instructorNames[$instructor['idx']] = $instructor['name'];
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
<h1>미디어 신청 내역</h1>
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
        // Fetch instructor names for the current board item
        $instructorIds = explode('|', $arrBoardList["list"][$i]['instructor']);
        $instructorNamesList = [];
        foreach ($instructorIds as $id) {
            if (isset($instructorNames[$id])) {
                $instructorNamesList[] = $instructorNames[$id];
            }
        }
        ?>
        <div class="sector">
            <table class="table">
                <tbody>
                <tr>
                    <td>강사</td>
                    <td><?=implode(', ', $instructorNamesList)?></td>
                </tr>
                <tr>
                    <td>이메일</td>
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
                    <td>비고</td>
                    <td><?=$arrBoardList["list"][$i]["contents"]?></td>
                </tr>
                <tr>
                    <td>유입경로</td>
                    <td><?=$referralText?></td>
                </tr>
                <tr>
                    <td>단체명(학교명)</td>
                    <td><?=$arrBoardList["list"][$i]["group_name"]?></td>
                </tr>
                <tr>
                    <td>주소</td>
                    <td><?=$arrBoardList["list"][$i]["address"]?></td>
                </tr>
                <tr>
                    <td>인원 수</td>
                    <td>총인원: <?=$arrBoardList["list"][$i]["total_members"]?>명 / 남: <?=$arrBoardList["list"][$i]["male_members"]?>명 / 여: <?=$arrBoardList["list"][$i]["female_members"]?>명</td>
                </tr>
                <tr>
                    <td>체험분야</td>
                    <td><?=implode(', ', explode('|', $arrBoardList["list"][$i]["experience"]))?></td>
                </tr>
                <tr>
                    <td>연령대</td>
                    <td><?=$arrBoardList["list"][$i]["age_group"]?></td>
                </tr>
                <tr>
                    <td>희망 날짜</td>
                    <td><?=$arrBoardList["list"][$i]["desired_date"]?></td>
                </tr>
                <tr>
                    <td>희망 이용시간</td>
                    <td>시작: <?=$arrBoardList["list"][$i]["start_hour"]?>시 <?=$arrBoardList["list"][$i]["start_minute"]?>분 / 종료: <?=$arrBoardList["list"][$i]["end_hour"]?>시 <?=$arrBoardList["list"][$i]["end_minute"]?>분</td>
                </tr>
                <tr>
                    <td>관리자 메모</td>
                    <td>
                        <?php if (!empty($arrBoardList["list"][$i]['child_admin'])): ?>
                            <table class="custom-table">
                                <colgroup>
                                    <col style="width: 70%;">
                                    <col style="width: 30%;">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th style="text-align:center;padding:20px 0;">내용</th>
                                    <th style="text-align:center;padding:20px 0;">생성일</th>
                                </tr>
                                </thead>
                                <tbody id="childlist">
                                <?
                                $arrChildAdmin		= explode("||",$arrBoardList["list"][$i]['child_admin']);
                                $arrChildWdate		= explode("||",$arrBoardList["list"][$i]['child_wdate']);
                                for($j=0;$j<count($arrChildAdmin);$j++){
                                    ?>
                                    <tr>
                                        <td><?=$arrChildAdmin[$j]?></td>
                                        <td><?=$arrChildWdate[$j]?></td>
                                    </tr>
                                    <?
                                }
                                ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </td>
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