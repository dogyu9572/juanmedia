<?
//session
function log_session($id){
	if(!$id){	jsGo("/member.php?goPage=Login","","�α��� �� �̿��Ͻʽÿ�.");	}
}

//memo insert
function insertMemo($to_id,$from_id){
	$tbl = $GLOBALS["_conf_tbl"]["memo_receive"];
	$tbl2 = $GLOBALS["_conf_tbl"]["member"];
	$tbl3 = $GLOBALS["_conf_tbl"]["memo_send"];

	$sql = "select * from $tbl2 where user_id='$from_id'";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	if($total_rs == 0){
		jsMsg("�α����� �ʿ��մϴ�.");
		return false;
	}

	$sql = "select * from $tbl2 where user_id='$to_id'";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs2 = mysqli_num_rows($rs);

	if($total_rs2 == 0){
		jsMsg("�������� �ʴ� ȸ���Դϴ�.");
		return false;
	}

	
	if($to_id == $from_id){
		jsMsg("�ڽſ��Դ� ������ ���� �� �����ϴ�.");
		return false;
	}

	//���������Կ�����
	$sql = "INSERT INTO ".$tbl." set ";
	$sql .= "user_id = '".$to_id."', ";
	$sql .= "from_user_id = '".$from_id."', ";
	$sql .= "content = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents'])."', ";
	$sql .= "is_notify = 'N', ";
	$sql .= "is_read = 'N', ";
	$sql .= "rdate = '', ";
	$sql .= "wdate = now()";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		//�������������Կ�����
		$sql = "INSERT INTO ".$tbl3." set ";
		$sql .= "r_idx = '$insert_idx', ";
		$sql .= "user_id = '".$from_id."', ";
		$sql .= "to_user_id = '".$to_id."', ";
		$sql .= "content = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents'])."', ";
		$sql .= "wdate = now() , ";
		$sql .= "is_read = 'N', ";
		$sql .= "rdate = ''";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total2 = mysqli_affected_rows($GLOBALS['dblink']);

		if($total2 >0){
			return true;
		}else{
			$sql = "DELETE FROM ".$tbl." WHERE idx='$insert_idx' ";
			mysqli_query($GLOBALS['dblink'], $sql);
			return false;
		}
	}	
}

//receivememo list
function receivememoList($from_id, $sw, $sk, $scale, $offset){
	$tbl1 = $GLOBALS["_conf_tbl"]["memo_receive"];

	$sql = "select count(idx) from ".$tbl1." where user_id='".$from_id."' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$row = mysql_fetch_row($rs);
    $total_rs = $row[0];
	
	$sql = "select * from ".$tbl1." where user_id='".$from_id."' ";

	if($total_rs > 0){
		$list['total'] = $total_rs;
        // ������ �׺���̼� ������ ����.
		    if(!$offset){
		        $offset=0;
		    }else{
		        $offset=$offset;
		    }

		    if($total_rs<=$offset){
		        $offset = $total_rs - $scale;
		    }

				//scale 0 ���� �����ÿ��� ��ü ������
				if($scale > 0){
			    $sql .= " order by wdate desc limit $offset,$scale ";
				}else{
				  $sql .= " order by main ";
				}

		    $rs = mysqli_query($GLOBALS['dblink'], $sql);
		    // offset �� �̿��� limit �� ����� ����
		    $total = mysqli_num_rows($rs);
		    $list['list']['total'] = $total;
		    // ������ �׺���̼� ������ ����.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
	return $list;
}

//sendmemo list
function sendmemoList($to_id, $sw, $sk, $scale, $offset){
	$tbl1 = $GLOBALS["_conf_tbl"]["memo_send"];

	$sql = "select count(idx) from ".$tbl1." where user_id='".$to_id."' ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$row = mysql_fetch_row($rs);
    $total_rs = $row[0];
	
	$sql = "select * from ".$tbl1." where user_id='".$to_id."' ";

	if($total_rs > 0){
		$list['total'] = $total_rs;
		    if(!$offset){
		        $offset=0;
		    }else{
		        $offset=$offset;
		    }

		    if($total_rs<=$offset){
		        $offset = $total_rs - $scale;
		    }

				//scale 0 ���� �����ÿ��� ��ü ������
				if($scale > 0){
			    $sql .= " order by wdate desc limit $offset,$scale ";
				}else{
				  $sql .= " order by main ";
				}

		    $rs = mysqli_query($GLOBALS['dblink'], $sql);
		    // offset �� �̿��� limit �� ����� ����
		    $total = mysqli_num_rows($rs);
		    $list['list']['total'] = $total;
		    // ������ �׺���̼� ������ ����.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
	return $list;
}

//receive memoview
function receivememoView($from_id, $stat, $idx){
	$tbl = $GLOBALS["_conf_tbl"]["memo_receive"];
	$tbl2 = $GLOBALS["_conf_tbl"]["memo_send"];

	if($stat=="N"){
		$sql  = "UPDATE $tbl SET ";
		$sql .= " is_read = 'Y', ";
		$sql .= " rdate = now() ";
		$sql .= "WHERE idx = '$idx' ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		$sql = "UPDATE $tbl2 set is_read = 'Y' , rdate = now() where idx='$idx'";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
	}
	
    $sql  = "SELECT * ";
    $sql .= "FROM $tbl ";
    $sql .= "WHERE idx = '$idx' ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);

	if($total_rs > 0){
		$list['total'] = $total_rs;
		for($i=0; $i < $total_rs; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
			$list['list'][$i][content] = nl2br(htmlspecialchars($list['list'][$i][content]));
		}
	}else{
		$list['total'] = 0;
	}
	return $list;
}

//send memoveiw
function sendmemoView($from_id, $stat, $idx){
	$tbl = $GLOBALS["_conf_tbl"]["memo_send"];

	$sql = "SELECT * from $tbl where idx='$idx' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	if($total_rs >0){
		$list['total'] = $total_rs;
		for($i=0;$i<$total_rs;$i++){
			$list['list'][$i] = mysqli_fetch_assoc($rs);
			$list['list'][$i][content] = nl2br(htmlspecialchars($list['list'][$i][content]));
		}
	}else{
		$list['total'] = 0;
	}
	return $list;
}

//delete memo
function deleteMemo($idx,$type,$id){
	
if($type=="receive"){
	$tbl = $GLOBALS["_conf_tbl"]["memo_receive"];

	$sql = "SELECT user_id from $tbl where idx='$idx' ";
	$r = mysqli_query($GLOBALS['dblink'], $sql);
	$rid = mysqli_fetch_array($r);
	if($id!=$rid[user_id]){
		jsGo("memo_view.php","","���� ������ �����Ͽ����ϴ�.");
	}

	$sql = "DELETE from $tbl where idx='$idx'";

}
if($type=="send"){
	$tbl = $GLOBALS["_conf_tbl"]["memo_send"];

	$sql = "SELECT user_id from $tbl where idx='$idx' ";

	$r = mysqli_query($GLOBALS['dblink'], $sql);
	$rid = mysqli_fetch_array($r);

	if($id!=$rid[user_id]){
		jsGo("memo_view.php","","���� ������ �����Ͽ����ϴ�.");
	}

	$sql = "DELETE from $tbl where idx='$idx'";
}
if($type=="save"){
	$tbl = $GLOBALS["_conf_tbl"]["memo_save"];

	$sql = "SELECT user_id from $tbl where idx='$idx' ";
	$r = mysqli_query($GLOBALS['dblink'], $sql);
	$rid = mysqli_fetch_array($r);

	if($id!=$rid[user_id]){
		jsGo("memo_view.php","","���� ������ �����Ͽ����ϴ�.");
	}

	$sql = "DELETE from $tbl where idx='$idx'";
}
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

//Insert save memo
function savereceivememoList($idx,$type){
	$tbl = $GLOBALS["_conf_tbl"]["memo_receive"];
	$tbl2 = $GLOBALS["_conf_tbl"]["memo_save"];
	
	$sql = "SELECT * from $tbl where idx='$idx' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$row = mysqli_fetch_array($rs);
	if($rs){
		
	//save
	$sql = "INSERT into $tbl2 set ";
	$sql .= "user_id = '$row[user_id]', ";
	$sql .= "from_user_id = '$row[from_user_id]', ";
	$sql .= "content = '$row[content]', ";
	$sql .= "wdate = '$row[wdate]' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	
	//delete
	$sql = "DELETE from $tbl where idx='$idx' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	
	if($rs){
		return true;
	}else{
		return false;
	}
	}
}

//savememoList view
function savememoList($from_id, $sw, $sk, $scale, $offset){
	$tbl1 = $GLOBALS["_conf_tbl"]["memo_save"];

	$sql = "select count(idx) from ".$tbl1." where user_id='".$from_id."' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$row = mysql_fetch_row($rs);
    $total_rs = $row[0];
	
	$sql = "select * from ".$tbl1." where user_id='".$from_id."' ";

	if($total_rs > 0){
		$list['total'] = $total_rs;
        // ������ �׺���̼� ������ ����.
		    if(!$offset){
		        $offset=0;
		    }else{
		        $offset=$offset;
		    }

		    if($total_rs<=$offset){
		        $offset = $total_rs - $scale;
		    }

				//scale 0 ���� �����ÿ��� ��ü ������
				if($scale > 0){
			    $sql .= " order by wdate desc limit $offset,$scale ";
				}else{
				  $sql .= " order by main ";
				}

		    $rs = mysqli_query($GLOBALS['dblink'], $sql);
		    // offset �� �̿��� limit �� ����� ����
		    $total = mysqli_num_rows($rs);
		    $list['list']['total'] = $total;
		    // ������ �׺���̼� ������ ����.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
	return $list;
}

//savememo view
function savememoView($from_id, $idx){
	$tbl = $GLOBALS["_conf_tbl"]["memo_save"];

	$sql = "SELECT * from $tbl where idx='$idx' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	if($total_rs >0){
		$list['total'] = $total_rs;
		for($i=0;$i<$total_rs;$i++){
			$list['list'][$i] = mysqli_fetch_assoc($rs);
			$list['list'][$i][content] = nl2br(htmlspecialchars($list['list'][$i][content]));
		}
	}else{
		$list['total'] = 0;
	}
	return $list;
}

//memo layer
function getmemoInfo($id){
	$tbl = $GLOBALS["_conf_tbl"]["memo_receive"];
	$sql = "select idx from $tbl where is_notify = 'N' And user_id='$id' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	if($total_rs >0){
		$list['total'] = $total_rs;
		for($i=0;$i<$total_rs;$i++){
			$list['list'][$i] = mysqli_fetch_assoc($rs);
		}
	}else{
		$list['total'] = 0;
	}

	return $list;
}

function setMemoNotifiy($id){
	$tbl = $GLOBALS["_conf_tbl"]["memo_receive"];
	$sql = "UPDATE $tbl set is_notify = 'Y' WHERE user_id='$id' AND is_notify ='N' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	
	if($rs){
		return true;
	}else{
		return false;
	}

}
?>
