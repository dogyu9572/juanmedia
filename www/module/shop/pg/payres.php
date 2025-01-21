<?php
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

	/*
     * [����������û ������(STEP2-2)]
     *
     * LG���÷������� ���� �������� LGD_PAYKEY(����Key)�� ������ ���� ������û.(�Ķ���� ���޽� POST�� ����ϼ���)
     */

	$configPath = $_SERVER['DOCUMENT_ROOT']."/module/shop/pg/lgdacom"; //LG�ڷ��޿��� ������ ȯ������("/conf/lgdacom.conf,/conf/mall.conf") ��ġ ����. 

    /*
     *************************************************
     * 1.�������� ��û - BEGIN
     *  (��, ���� �ݾ�üũ�� ���Ͻô� ��� �ݾ�üũ �κ� �ּ��� ���� �Ͻø� �˴ϴ�.)
     *************************************************
     */
    $CST_PLATFORM               = $HTTP_POST_VARS["CST_PLATFORM"];
    $CST_MID                    = $HTTP_POST_VARS["CST_MID"];
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;
    $LGD_PAYKEY                 = $HTTP_POST_VARS["LGD_PAYKEY"];

    require_once("./lgdacom/XPayClient.php");
    $xpay = &new XPayClient($configPath, $CST_PLATFORM);
    $xpay->Init_TX($LGD_MID);    
    
    $xpay->Set("LGD_TXNAME", "PaymentByKey");
    $xpay->Set("LGD_PAYKEY", $LGD_PAYKEY);
    
    //�ݾ��� üũ�Ͻñ� ���ϴ� ��� �Ʒ� �ּ��� Ǯ� �̿��Ͻʽÿ�.
	//$DB_AMOUNT = "DB�� ���ǿ��� ������ �ݾ�"; //�ݵ�� �������� �Ұ����� ��(DB�� ����)���� �ݾ��� �������ʽÿ�.
	//$xpay->Set("LGD_AMOUNTCHECKYN", "Y");
	//$xpay->Set("LGD_AMOUNT", $DB_AMOUNT);
	    
    /*
     *************************************************
     * 1.�������� ��û(�������� ������) - END
     *************************************************
     */

    /*
     * 2. �������� ��û ���ó��
     *
     * ���� ������û ��� ���� �Ķ���ʹ� �����޴����� �����Ͻñ� �ٶ��ϴ�.
     */
    if ($xpay->TX()) {
        //1)������� ȭ��ó��(����,���� ��� ó���� �Ͻñ� �ٶ��ϴ�.)
        /*
		echo "������û�� �Ϸ�Ǿ����ϴ�.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
            
        echo "�ŷ���ȣ : " . $xpay->Response("LGD_TID",0) . "<br>";
        echo "�������̵� : " . $xpay->Response("LGD_MID",0) . "<br>";
        echo "�����ֹ���ȣ : " . $xpay->Response("LGD_OID",0) . "<br>";
        echo "�����ݾ� : " . $xpay->Response("LGD_AMOUNT",0) . "<br>";
        echo "����ڵ� : " . $xpay->Response("LGD_RESPCODE",0) . "<br>";
        echo "����޼��� : " . $xpay->Response("LGD_RESPMSG",0) . "<p>";
        */
		
        $keys = $xpay->Response_Names();
        foreach($keys as $name) {
            //echo $name . " = " . $xpay->Response($name, 0) . "<br>";
        }
          
        //echo "<p>";
           
        if( "0000" == $xpay->Response_Code() ) {
         	//����������û ��� ���� DBó��
           	//echo "����������û ��� ���� DBó���Ͻñ� �ٶ��ϴ�.<br>";
			
			$LGD_MERTKEY	= "c74d660c2700db542c59e77b9244173a";
			$authdata = md5($xpay->Response("LGD_MID",0).$xpay->Response("LGD_TID",0).$LGD_MERTKEY);

			$orderid = $xpay->Response("LGD_OID",0);
			$ipkum_date = date("Y-m-d H:i:s");

			$dblink = SetConn($_conf_db["main_db"]);
			
			$arrInfo = getOrderInfoAdmin($orderid);
			
			if($arrInfo["list"][0]["pay_type"]=="escrow") {
				$sql = "update tbl_shop_order_info set order_state='1' where order_no='$orderid'";
			} else {
				$sql = "update tbl_shop_order_info set order_state='6',ipkum_date='$ipkum_date',lg_tid='".$xpay->Response("LGD_TID",0)."',lg_auth='$authdata' where order_no='$orderid'";
			}
			$rs = mysql_query($sql);
			$total = mysqli_affected_rows($GLOBALS['dblink']);

			if($total > 0){
				$isDBOK = true; //DBó�� ���н� false�� ������ �ּ���.
			} else {
				$isDBOK = false; //DBó�� ���н� false�� ������ �ּ���.
			}

			SetDisConn($dblink);

            //����������û ��� ���� DBó�� ���н� Rollback ó��
          	//$isDBOK = true; //DBó�� ���н� false�� ������ �ּ���.
          	if( !$isDBOK ) {
           		echo "<p>";
           		$xpay->Rollback("���� DBó�� ���з� ���Ͽ� Rollback ó�� [TID:" . $xpay->Response("LGD_TID",0) . ",MID:" . $xpay->Response("LGD_MID",0) . ",OID:" . $xpay->Response("LGD_OID",0) . "]");            		            		
            		
                echo "TX Rollback Response_code = " . $xpay->Response_Code() . "<br>";
                echo "TX Rollback Response_msg = " . $xpay->Response_Msg() . "<p>";
            		
                if( "0000" == $xpay->Response_Code() ) {
                  	jsGo("/shop.php?goPage=Cart","parent","�ڵ���Ұ� ���������� �Ϸ� �Ǿ����ϴ�.");
                }else{
          			jsGo("/shop.php?goPage=Cart","parent","�ڵ���Ұ� ���������� ó������ �ʾҽ��ϴ�.");
                }
          	}     
			
			jsGo("/shop.php?goPage=Thanks&order_no=".$orderid,"parent","");

        }else{
          	//����������û ��� ���� DBó��
         	echo "<script>alert('�������� �����Ͽ����ϴ�.'); history.go(-1);</script>";     
	        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
	        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";

        }
    }else {
        //2)API ��û���� ȭ��ó��
        echo "������û�� �����Ͽ����ϴ�.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
            
        //����������û ��� ���� DBó��
        echo "����������û ��� ���� DBó���Ͻñ� �ٶ��ϴ�.<br>";            	                        
    }
?>
