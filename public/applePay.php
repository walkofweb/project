<!DOCTYPE html>
<html>
 
<head>
    <script src="https://assets.ottu.net/checkout/v2/checkout.min.js" 
     data-error="errorCallback" data-cancel="cancelCallback"
     data-success="successCallback" 
     data-beforeredirect="beforeRedirect"></script>
 </head>
 
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
   <a class="navbar-brand" >
     <img src="https://ottu.com/img/logo.svg" style="width: 100px;transition: all 0.5s ease-in-out;">
       
     </a>
  </div>
</nav>
<div class="container">
  <div class="row">
    <div class="col text-center p-2" id="loading">
      <div class="spinner-border" style="color:red" role="status">
        <span class="visually-hidden"></span>
      </div>
    </div>
      
  </div>
  <div class="row" >
    <div class="col " style="padding:20px">
      <div id="checkout" ></div>
    </div>
  </div>
</div>
<script type="text/javascript">
  window.errorCallback = function(error) {
  console.log('applw pay error callback', error)
  if (error.redirect_url)
    window.location.href = error.redirect_url
}

window.successCallback = function(success) {
  window.location.href = success.redirect_url
  if (success.data.redirect_url)
    window.location.href = success.data.redirect_url
}

window.cancelCallback = function(cancel) {
  Checkout.reload()
  console.log('cancel callback', cancel)
}
async function getCheckoutSessionId() {
  const response = await fetch('https://sandbox.ottu.net/b/checkout/v1/pymt-txn/', {
    method: 'POST',
    headers: {
      'Authorization': 'Api-Key vSUmxsXx.V81oYvOWFMcIywaOu57Utx6VSCmG11lo',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      "type": "payment_request",
      "pg_codes": ["apple-pay"],
      "amount": "20",
      "customer_id":"sandbox",
      "currency_code": "KWD"
    })
  });
  const responseData = await response.json();
  return responseData.session_id;
}

getCheckoutSessionId().then((sessionid)=>{

document.getElementById('loading').classList.add('d-none')
Checkout.init({
  selector: 'checkout',
  merchant_id: 'sandbox.ottu.net',
  session_id: sessionid,
  apiKey: '76d1b84ee3f47623720dc0c5f690cfa623908349',
  formsOfPayment: ['applePay'],
  css: `
    .ottu__sdk-main {
      flex-basis: 100%;
      justify-content: center;
    }
    .ottu__sdk-header{
      font-family: system-ui !important;
    }
    .ottu__sdk-amount{
      font-family: system-ui !important;
    }
    .ottu__sdk-total{
      font-family: system-ui !important;
     }
    .ottu__sdk-title{
      font-family: system-ui !important;
    }
    .ottu__sdk-subtitle{
      font-family: system-ui !important;
    }
    .ottu__sdk-payment-name{
      font-family: system-ui !important;
    }
    .ottu__sdk-green{
      font-family: system-ui !important;
    }
     .apple-pay-button {
      width: 90%;
      padding-top: 0px;
      margin-top: 12px;
      margin-bottom: 12px;
    }
  `
});
});
</script>

</html>