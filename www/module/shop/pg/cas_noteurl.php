<?php
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
    /*
     * [���� �������ó��(DB) ������]
     *
     * 1) ������ ������ ���� hashdata�� ������ �ݵ�� �����ϼž� �մϴ�.
     *
     */
    $LGD_RESPCODE            = $HTTP_POST_VARS["LGD_RESPCODE"];             // �����ڵ�: 0000(����) �׿� ����
    $LGD_RESPMSG             = $HTTP_POST_VARS["LGD_RESPMSG"];              // ����޼���
    $LGD_MID                 = $HTTP_POST_VARS["LGD_MID"];                  // �������̵�
    $LGD_OID                 = $HTTP_POST_VARS["LGD_OID"];                  // �ֹ���ȣ
    $LGD_AMOUNT              = $HTTP_POST_VARS["LGD_AMOUNT"];               // �ŷ��ݾ�
    $LGD_TID                 = $HTTP_POST_VARS["LGD_TID"];                  // LG���÷������� �ο��� �ŷ���ȣ
    $LGD_PAYTYPE             = $HTTP_POST_VARS["LGD_PAYTYPE"];              // ���������ڵ�
    $LGD_PAYDATE             = $HTTP_POST_VARS["LGD_PAYDATE"];              // �ŷ��Ͻ�(�����Ͻ�/��ü�Ͻ�)
    $LGD_HASHDATA            = $HTTP_POST_VARS["LGD_HASHDATA"];             // �ؽ���
    $LGD_FINANCECODE         = $HTTP_POST_VARS["LGD_FINANCECODE"];          // ��������ڵ�(�����ڵ�)
    $LGD_FINANCENAME         = $HTTP_POST_VARS["LGD_FINANCENAME"];          // ��������̸�(�����̸�)
    $LGD_ESCROWYN            = $HTTP_POST_VARS["LGD_ESCROWYN"];             // ����ũ�� ���뿩��
    $LGD_TIMESTAMP           = $HTTP_POST_VARS["LGD_TIMESTAMP"];            // Ÿ�ӽ�����
    $LGD_ACCOUNTNUM          = $HTTP_POST_VARS["LGD_ACCOUNTNUM"];           // ���¹�ȣ(�������Ա�)
    $LGD_CASTAMOUNT          = $HTTP_POST_VARS["LGD_CASTAMOUNT"];           // �Ա��Ѿ�(�������Ա�)
    $LGD_CASCAMOUNT          = $HTTP_POST_VARS["LGD_CASCAMOUNT"];           // ���Աݾ�(�������Ա�)
    $LGD_CASFLAG             = $HTTP_POST_VARS["LGD_CASFLAG"];              // �������Ա� �÷���(�������Ա�) - 'R':�����Ҵ�, 'I':�Ա�, 'C':�Ա����
    $LGD_CASSEQNO            = $HTTP_POST_VARS["LGD_CASSEQNO"];             // �Աݼ���(�������Ա�)
    $LGD_CASHRECEIPTNUM      = $HTTP_POST_VARS["LGD_CASHRECEIPTNUM"];       // ���ݿ����� ���ι�ȣ
    $LGD_CASHRECEIPTSELFYN   = $HTTP_POST_VARS["LGD_CASHRECEIPTSELFYN"];    // ���ݿ����������߱������� Y: �����߱��� ����, �׿� : ������
    $LGD_CASHRECEIPTKIND     = $HTTP_POST_VARS["LGD_CASHRECEIPTKIND"];      // ���ݿ����� ���� 0: �ҵ������ , 1: ����������
	$LGD_PAYER     			 = $HTTP_POST_VARS["LGD_PAYER"];      			// �Ա��ڸ�
	
    /*
     * ��������
     */
    $LGD_BUYER               = $HTTP_POST_VARS["LGD_BUYER"];                // ������
    $LGD_PRODUCTINFO         = $HTTP_POST_VARS["LGD_PRODUCTINFO"];          // ��ǰ��
    $LGD_BUYERID             = $HTTP_POST_VARS["LGD_BUYERID"];              // ������ ID
    $LGD_BUYERADDRESS        = $HTTP_POST_VARS["LGD_BUYERADDRESS"];         // ������ �ּ�
    $LGD_BUYERPHONE          = $HTTP_POST_VARS["LGD_BUYERPHONE"];           // ������ ��ȭ��ȣ
    $LGD_BUYEREMAIL          = $HTTP_POST_VARS["LGD_BUYEREMAIL"];           // ������ �̸���
    $LGD_BUYERSSN            = $HTTP_POST_VARS["LGD_BUYERSSN"];             // ������ �ֹι�ȣ
    $LGD_PRODUCTCODE         = $HTTP_POST_VARS["LGD_PRODUCTCODE"];          // ��ǰ�ڵ�
    $LGD_RECEIVER            = $HTTP_POST_VARS["LGD_RECEIVER"];             // ������
    $LGD_RECEIVERPHONE       = $HTTP_POST_VARS["LGD_RECEIVERPHONE"];        // ������ ��ȭ��ȣ
    $LGD_DELIVERYINFO        = $HTTP_POST_VARS["LGD_DELIVERYINFO"];         // �����
      
	$LGD_MERTKEY = "c74d660c2700db542c59e77b9244173a";  //LG���÷������� �߱��� ����Ű�� ������ �ֽñ� �ٶ��ϴ�.
	$LGD_MID 		= "jarakr";
    $LGD_HASHDATA2 = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_RESPCODE.$LGD_TIMESTAMP.$LGD_MERTKEY);
    
    /*
     * ���� ó����� ���ϸ޼���
     *
     * OK  : ���� ó����� ����
     * �׿� : ���� ó����� ����
     *
     * �� ���ǻ��� : ������ 'OK' �����̿��� �ٸ����ڿ��� ���ԵǸ� ����ó�� �ǿ��� �����Ͻñ� �ٶ��ϴ�.
     */
    $resultMSG = "������� ���� DBó��(LGD_CASNOTEURL) ������� �Է��� �ֽñ� �ٶ��ϴ�.";

    
    if ( $LGD_HASHDATA2 == $LGD_HASHDATA ) { //�ؽ��� ������ �����̸�
        if ( "0000" == $LGD_RESPCODE ){ //������ �����̸�
        	if( "R" == $LGD_CASFLAG ) {
                /*
                 * ������ �Ҵ� ���� ��� ���� ó��(DB) �κ�
                 * ���� ��� ó���� �����̸� "OK"
                 */    
                //if( ������ �Ҵ� ���� ����ó����� ���� ) 

				$dblink = SetConn($_conf_db["main_db"]);
				$sql = "update tbl_shop_order_info set bank_type='".iconv("euc-kr","utf-8",$LGD_FINANCENAME)." ".$LGD_ACCOUNTNUM."', bank_name='".iconv("euc-kr","utf-8",$LGD_PAYER)."' where order_no='$LGD_OID'";
				$rs = mysql_query($sql);
				SetDisConn($dblink);

                $resultMSG = "OK";   
        	}else if( "I" == $LGD_CASFLAG ) {

				$dblink = SetConn($_conf_db["main_db"]);
				
				$sql = "UPDATE tbl_shop_order_info SET
					order_state='6', ipkum_date=now()
					WHERE order_no='$LGD_OID'
				";
				$rs = mysqli_query($GLOBALS['dblink'], $sql);

				
				$sql1 = "UPDATE  tbl_shop_order_good SET order_status='6' WHERE order_no='$LGD_OID'";
				$rs1 = mysql_query($sql1, $GLOBALS['dblink']);

				SetDisConn($dblink);


 	            /*
    	         * ������ �Ա� ���� ��� ���� ó��(DB) �κ�
        	     * ���� ��� ó���� �����̸� "OK"
            	 */    
            	//if( ������ �Ա� ���� ����ó����� ���� ) 
            	$resultMSG = "OK";
        	}else if( "C" == $LGD_CASFLAG ) {
 	            /*
    	         * ������ �Ա���� ���� ��� ���� ó��(DB) �κ�
        	     * ���� ��� ó���� �����̸� "OK"
            	 */    
            	//if( ������ �Ա���� ���� ����ó����� ���� ) 
            	$resultMSG = "OK";
        	}
        } else { //������ �����̸�
            /*
             * �ŷ����� ��� ���� ó��(DB) �κ�
             * ������� ó���� �����̸� "OK"
             */  
            //if( �������� ����ó����� ���� ) 
            $resultMSG = "OK";     
        }
    } else { //�ؽ����� ������ �����̸�
        /*
         * hashdata���� ���� �α׸� ó���Ͻñ� �ٶ��ϴ�. 
         */      
        $resultMSG = "������� ���� DBó��(LGD_CASNOTEURL) �ؽ��� ������ �����Ͽ����ϴ�.";     
    }
    
    echo $resultMSG;
?>
