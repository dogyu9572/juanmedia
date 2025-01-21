<script src="https://pay.nicepay.co.kr/v1/js/"></script> <!-- Server 승인 운영계 -->
 
 
<script>
function serverAuth() {

  AUTHNICE.requestPay({
    clientId: 'S2_af4543a0be4d49a98122e01ec2059a56',
    method: 'card',
    orderId: 'b9384f25-3cc1-487e-bbd0-c6437125ea36',
    amount: 1004,
    goodsName: '나이스페이-상품',
    returnUrl: 'http://localhost:3000/serverAuth', //API를 호출할 Endpoint 입력
    fnError: function (result) {
      alert('개발자확인용 : ' + result.errorMsg + '')
    }
 });
}
</script>

<button onclick="serverAuth()">serverAuth 결제하기</button> 