<style>

@import url('https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,700;1,300&display=swap');

* {
    font-family: 'Titillium Web', sans-serif;
    color: #4e4e4e;
    line-height: 1.5;
  }

  body {
    background: #f3f3f3;
  }

  ul {
      list-style: none;
      padding-left: 0;
  }
  

  li {
      line-height: 3;
  }

  .table {
    width: 100%;
  }

  th, td {
    padding: 15px;
    margin-left: 0;
    padding-left: 0;
    margin-right: 18px;
    text-align: left;
    padding-right: 128px;
  }

.wg-info {
    margin-left: 14em;
}

.wg-card {
    max-width: 54%;
    margin: 16px;
    background: #FFFFFF;
    padding: 2em;
    border-radius: 3px;
    -webkit-box-shadow: 0px 3px 5px 0px rgba(50, 50, 50, 0.2);
    -moz-box-shadow:    0px 3px 5px 0px rgba(50, 50, 50, 0.2);
    box-shadow:         0px 3px 5px 0px rgba(50, 50, 50, 0.2);
}

.container > div {
    margin: 2% auto;
    padding-top: 1px;
    padding-bottom: 20px;
}

.wg-box {
    max-width: 60%;
    margin: 16px;
    padding: 0em;
}

.group { 
  position:relative; 
  margin-bottom:45px; 
}

input {
  font-size:18px;
  padding:10px 10px 10px 5px;
  display:block;
  width:300px;
  border:none;
  border-bottom:1px solid #757575;
}

input:focus { 
    outline:none;
    border-bottom:1px solid #D05D1C;
}

label 				 {
  color:#999; 
  font-size:18px;
  font-weight:normal;
  position:absolute;
  pointer-events:none;
  left:5px;
  top:10px;
  transition:0.2s ease all; 
  -moz-transition:0.2s ease all; 
  -webkit-transition:0.2s ease all;
}


input:focus ~ label, input:valid ~ label 		{
  top:-20px;
  font-size:14px;
  color:#5264AE;
}

.bar 	{ position:relative; display:block; width:300px; }
.bar:before, .bar:after 	{
  content:'';
  height:2px; 
  width:0;
  bottom:1px; 
  position:absolute;
  background:#5264AE; 
  transition:0.2s ease all; 
  -moz-transition:0.2s ease all; 
  -webkit-transition:0.2s ease all;
}

input:focus ~ .bar:before, input:focus ~ .bar:after {
  width:50%;
}

.highlight {
  position:absolute;
  height:60%; 
  width:100px; 
  top:25%; 
  left:0;
  pointer-events:none;
  opacity:0.5;
}

input:focus ~ .highlight {
  -webkit-animation:inputHighlighter 0.3s ease;
  -moz-animation:inputHighlighter 0.3s ease;
  animation:inputHighlighter 0.3s ease;
}

@-webkit-keyframes inputHighlighter {
	from { background:#5264AE; }
  to 	{ width:0; background:transparent; }
}
@-moz-keyframes inputHighlighter {
	from { background:#5264AE; }
  to 	{ width:0; background:transparent; }
}
@keyframes inputHighlighter {
	from { background:#5264AE; }
  to 	{ width:0; background:transparent; }
}

.button {
  font-family: 'Titillium Web', sans-serif;
}

.button {
  background: #B6B6B6;
  box-shadow: 0px 0px 6px #0000000A;
  border-radius: 4px;
  width: 100%;border: none;
  height: 40px;
  color: #FFF;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s ease-in-out;
}

.button:hover {
  box-shadow: 0px 3px 6px #FFAA69;
}

.button-active {
  background: #D05D1C;
  box-shadow: 0px 0px 6px #0000000A;
  border-radius: 4px;
  width: 100%;border: none;
  height: 40px;
  color: #FFF;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s ease-in-out;
}

.button-disabled,.button:disabled {
  background: #B6B6B6 0% 0% no-repeat padding-box;
  pointer-events: none; 
}

.wg-secure {
  margin-left: auto;
  margin-right: auto;
  text-align: center;
  padding-bottom: 60px;
}

.wg-secure > p {
  font-size: 12px;
}

a {
  text-decoration: underline;
  font: normal normal bold 18px/27px Titillium Web;
  letter-spacing: 0px;
  color: #1363A5;
  opacity: 1;
}

.wg-info {
  text-align: end; 
  padding-right: 0px;
  font-weight: 100;
}

.wg-amount {
    text-align: end;
    padding: 0px;
    font-size: 22px;
}

.icon {
    width: 128px;
    padding-right: 24px;
}

.icon {
  position: relative;
  top: 8px;
  left: 2px;
  right: auto;
}

.input-container {
  display: inline-block;
}

.float {
  float: left;
}

.wg-state {
  text-align: center;
}

.wg-state-icon  {
  width: 48px;
}

.wg-state > h5 {
  font-weight: bold;
}

.wg-table-info {
  font-size: 20px;
  font-weight: 100;
}

.wg-full {
  width: 100%;
}

.timer-progress {
  border: 5px solid #11559C;
  border-radius: 64px;
  padding: 2%;
  width: 32px;
}

.clock {
  color: #11559C;
  font-size: 16px;
  font-weight: bold;
}

.error-text {
  font-size: 15px;
}

/**
*** SIMPLE GRID
*** (C) ZACH COLE 2016
**/

/* UNIVERSAL */

html,
body {
  height: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
  left: 0;
  top: 0;
  font-size: 100%;
}

/* TYPOGRAPHY */

h1 {
  font-size: 2.5rem;
}

h2 {
  font-size: 2rem;
}

h3 {
  font-size: 1.375rem;
}

h4 {
  font-size: 1.125rem;
}

h5 {
  font-size: 1rem;
}

h6 {
  font-size: 0.875rem;
}

p {
  font-size: 1.125rem;
  font-weight: 200;
  line-height: 1.8;
}

.font-light {
  font-weight: 300;
}

.font-regular {
  font-weight: 400;
}

.font-heavy {
  font-weight: 700;
}

/* POSITIONING */

.left {
  text-align: left;
}

.right {
  text-align: right;
}

.center {
  text-align: center;
  margin-left: auto;
  margin-right: auto;
}

.justify {
  text-align: justify;
}

/* ==== GRID SYSTEM ==== */

.container {
  width: 90%;
  margin-left: auto;
  margin-right: auto;
}

.row {
  position: relative;
  width: 100%;
}

.row [class^="col"] {
  float: left;
  margin: 0.5rem 2%;
  min-height: 0.125rem;
}

#retry {
    display: none;
}

.col-1,
.col-2,
.col-3,
.col-4,
.col-5,
.col-6,
.col-7,
.col-8,
.col-9,
.col-10,
.col-11,
.col-12 {
  width: 96%;
}

.col-1-sm {
  width: 4.33%;
}

.col-2-sm {
  width: 12.66%;
}

.col-3-sm {
  width: 21%;
}

.col-4-sm {
  width: 29.33%;
}

.col-5-sm {
  width: 37.66%;
}

.col-6-sm {
  width: 46%;
}

.col-7-sm {
  width: 54.33%;
}

.col-8-sm {
  width: 62.66%;
}

.col-9-sm {
  width: 71%;
}

.col-10-sm {
  width: 79.33%;
}

.col-11-sm {
  width: 87.66%;
}

.col-12-sm {
  width: 96%;
}

.row::after {
	content: "";
	display: table;
	clear: both;
}

.hidden-sm {
  display: none;
}

.number-notice {
    margin-top: 0px;
    font-size: smaller;
}

.number-request {
    margin-bottom: 0px;
}

@media only screen and (min-width: 33.75em) {  /* 540px */
  .container {
    width: 80%;
  }
}

@media only screen and (min-width: 45em) {  /* 720px */
  .col-1 {
    width: 4.33%;
  }

  .col-2 {
    width: 12.66%;
  }

  .col-3 {
    width: 21%;
  }

  .col-4 {
    width: 29.33%;
  }

  .col-5 {
    width: 37.66%;
  }

  .col-6 {
    width: 46%;
  }

  .col-7 {
    width: 54.33%;
  }

  .col-8 {
    width: 62.66%;
  }

  .col-9 {
    width: 71%;
  }

  .col-10 {
    width: 79.33%;
  }

  .col-11 {
    width: 87.66%;
  }

  .col-12 {
    width: 96%;
  }

  .hidden-sm {
    display: block;
  }
}

@media only screen and (min-width: 60em) { /* 960px */
  .container {
    width: 75%;
    max-width: 60rem;
  }
}



</style>

<?php
/**
 *
 * Template Name: vPOS Checkout Page
 */

date_default_timezone_set("Africa/Luanda");

if (empty($_COOKIE['vpos_merchant'])) {
  echo("<script>location.href = '". site_url() ."'</script>");
} 
?>

<!DOCTYPE html>
<html>
  <head>
    <title>vPOS Checkout - <?php if ($_COOKIE['vpos_merchant'] != "") { echo $_COOKIE['vpos_merchant']; } else { echo ""; } ?></title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/google-libphonenumber@3.2.17/dist/libphonenumber.js" integrity="sha256-y7g6xQm+MB2sFTvdhBwEMDWg9sAUz9msCc2973e0wjg=" crossorigin="anonymous"></script>
  <head>
  <body onload="init();">
  <div class="wg-container container">
            <div class="wg-card">
                <h3>Detalhes do pagamento</h3>
                <table class="table">
                    <tr>
                        <th>Data da compra: </th>
                        <th class="wg-info"><?php echo date("d/m/Y"); ?></th>
                    </tr>
                    <tr>
                        <th>Comerciante: </th>
                        <th class="wg-info"><?php if ($_COOKIE['vpos_merchant'] != "") { echo $_COOKIE['vpos_merchant']; } else { echo ""; } ?></th>
                    </tr>
                    <tr>
                        <th>M??todo de pagamento: </th>
                        <th class="wg-info">Multicaixa Express</th>
                    </tr>
                </table>
            </div>

            <div id='state' class="wg-card">
                <div class="wg-notice">
                    <h5 class="number-request">Digite o seu n??mero de telem??vel</h5>
                    <p class="number-notice">O n??mero introduzido deve estar registado no Multicaixa Express</p>
                </div>

                <div class="input-container">
                    <img class="icon float" src="https://backoffice.vpos.ao/images/mcx-logo.svg">
                    <?php 
                        if (empty($_COOKIE['vpos_order_billing_telephone'])) {
                          echo("<input oninput='checkMobileNumber()' class='float' id='mobile' name='telephone' type='text' placeholder='Digite o seu n??mero de telem??vel' maxlength='9' required></span>");
                        } else {
                          echo("<input oninput='checkMobileNumber()' value='" . $_COOKIE['vpos_order_billing_telephone'] . "' class='float' id='mobile' name='telephone' type='text' placeholder='Digite o seu n??mero de telem??vel' maxlength='9' required></span>");
                        }
                    ?> 
                    
                </div>
            </div>

            <div class="wg-box">
                <div class="wg-full">
                    <table class="wg-full">
                        <tbody id="summary-table">
                          <tr>
                              <th class="wg-table-info">Montante: </th>
                              <th class="wg-amount"><?php echo formatTotalAmount($_COOKIE['vpos_total_amount']); ?></th>
                          </tr>
                        </tbody>
                    </table>
                    <button type="button" id="submit" class="button">CONFIRMAR</button>
                    <button type="button" id="retry" class="button" onclick="reload()">TENTAR NOVAMENTE</button>
                    <div class="center">
                        <!-- The p tag below should not be removed or changed for trademark reasons. Please refer to GNU General Public License v3 -->
                        <p>Powered by <a href="https://vpos.ao" target="_blank">vPOS</a></p>
                    </div>
                    <div class="wg-secure">
                        <p>Todos os pagamentos via vPOS s??o seguros <img src="https://backoffice.vpos.ao/images/mcx-lock.svg"></p> 
                    </div>
                </div>
            </div>
        </div>
        <div class="wg-secure">
            <a id="url" href=<?php echo wc_get_checkout_url(); ?>
            >Voltar para o Checkout </a>
        </div>
    <script>

    const ONE_SECOND = 1000;
    var mobile = "";
    var state = "initial";
    var timer = null;
    var numberIsAdded = false;
    const payments_url = "<?php echo get_rest_url(null, "vpos-woocommerce/v1/payment"); ?>";
    const poll_url = "<?php echo home_url(). '/payment'; ?>";
  
    function isValidPhoneNumber(mobile) {
      if (isSandboxNumber(mobile)) {
        return true;
      } else {
        var phoneUtil = libphonenumber.PhoneNumberUtil.getInstance();
        var number = phoneUtil.parse("+244" + mobile);
        return phoneUtil.isValidNumberForRegion(number, "AO");
      }
    }

    function isSandboxNumber(mobile) {
      switch (mobile) {
        case '900000000':
          return true;
        case '900002004':
          return true;
        case '900003000':
          return true;
        default:
          return false;
      }
    }

    function checkMobileNumber() {
      this.mobile = document.getElementById("mobile").value;
      if (this.isValidPhoneNumber(this.mobile) == true) {
        document.getElementById("submit").classList.add("button-active");
        document.getElementById("submit").classList.remove("button-disabled");
      } else {
        document.getElementById("submit").classList.remove("button-active");
        document.getElementById("submit").classList.add("button-disabled");
      }
    }

    function reload() {
        location.reload();
    }

    function headers() {
      return {
        headers: {
          "Content-Type": "multipart/form-data"
        }
      };
    }

    function showErrorMessage(status_reason) {
      switch(status_reason) {
        case "3000":
          document.getElementById("error-message").innerText = "Pagamento recusado pelo cliente";
          break;
        case "2004":
          document.getElementById("error-message").innerText = "Tempo limite de pagamento esgotado";
          break;
        case "2003":
          document.getElementById("error-message").innerText = "Limite de rede ou de cart??o ultrapassado";
          break;
        case "2002":
          document.getElementById("error-message").innerText = "Pagamento recusado pelo emissor de cart??o";
          break;
        case "2001":
          document.getElementById("error-message").innerText = "Pagamento recusado por saldo insuficiente na conta";
          break;
        case "2000":
          document.getElementById("error-message").innerText = "Processador de pagamentos n??o dispon??vel";
          break;
        case "1000":
          document.getElementById("error-message").innerText = "Servi??o n??o dispon??vel";
          break;
        default:
          document.getElementById("error-message").innerText = "Erro desconhecido";
        }
    }

    function sendPaymentRequest(amount, mobile) {
      document.getElementById("submit").disabled = true
      return axios.post(payments_url, {
        mobile: mobile,
        amount: amount
      },headers)
      .then(response => {
          const transaction_id = response.data;
          const redirect_url = "<?php echo get_rest_url(null, "vpos-woocommerce/v1/cart/vpos/"); ?>" + transaction_id;
          window.location.href = poll_url + "?id=" + transaction_id;
        return response;
      }).catch(error => {
        if (this.state == "initial") {
          this.state = "error";
          addMobileNumberToSummaryTable(mobile);
          var stateComponent = document.getElementById("state");
          var state = errorComponent();
          stateComponent.replaceWith(state);
          document.getElementById("submit").textContent = "TENTAR NOVAMENTE";
        }
        return error;
      });
    }

    function formatMobileNumber(mobile) {
        let formattedMobile = mobile.match(/.{1,3}/g);
        return formattedMobile.join(' ');
    }

    function addMobileNumberToSummaryTable(mobile) {
      if (this.state == "processing" && this.numberIsAdded == false) {
        var table = document.getElementById("summary-table");
        var firstTr = document.createElement("tr");
        const th = document.createElement("th");
        th.innerHTML = "Telem??vel: ";
        th.classList.add("wg-table-info");
        firstTr.appendChild(th);

        const mobileTh = document.createElement("th");
        mobileTh.innerHTML = formatMobileNumber(mobile);
        mobileTh.classList.add("wg-amount");
        firstTr.appendChild(mobileTh);
        table.appendChild(firstTr);
        this.numberIsAdded = true;
      }
    }

    function errorComponent() {
      const state = document.createElement("div");
      state.id = "state";
      state.classList.add("wg-card");
      state.innerHTML = "<div class='wg-state'><h5>Ocorreu um erro ao efectuar o pagamento</h5><p class='error-text' id='error-message'></p><img class='wg-state-icon' src='https://backoffice.vpos.ao/images/error.png'></div>";
      return state;
    }

    function expiredComponent() {
      const state = document.createElement("div");
      state.id = "state";
      state.classList.add("wg-card");
      state.classList.add("wg-state");
      document.getElementById("submit").style.display = "initial";
      document.getElementById("submit").textContent = "TENTAR NOVAMENTE";
      state.innerHTML = "<h5>Tempo Esgotado</h5><img class='wg-state-icon' src='https://backoffice.vpos.ao/images/warning.png'>"
      return state;
    }

    function succeedComponent() {
      const state = document.createElement("div");
      state.id = "state";
      state.classList.add("wg-card");
      state.classList.add("wg-state");
      document.getElementById("submit").style.display = "none";
      state.innerHTML = "<h5>Pagamento efectuado com sucesso</h5><img class='wg-state-icon' src='https://backoffice.vpos.ao/images/confirmed.png'>"
      return state;
    }

    function confirmationComponent() {
      const state = document.createElement("div");
      state.id = "state";
      state.classList.add("wg-card");
      state.innerHTML = "<div class='wg-state'><h5>Confirme o Pagamento no seu telem??vel</h5><p id='timer' class='clock'>0:00</p></div>"
      return state;
    }

    document.getElementById("submit").addEventListener("click", function() {
      if (this.mobile == "" || this.mobile == null) {
        this.mobile = document.getElementById("mobile").value;
      } 

      const total_amount = <?php echo $_COOKIE['vpos_total_amount']; ?>;
      if (isValidPhoneNumber(this.mobile)) {
        sendPaymentRequest(total_amount, this.mobile);
      }
    });


    function init() {
      checkMobileNumber();
      console.log('%cThis site uses vPOS to enable payments. Register and start your journey with us. %chttps://vpos.ao', 'font-weight:bold', 'color: red');
    }
    </script>
  </body>
</html>