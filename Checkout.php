<?php
// Verifique se há dados do carrinho no POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['carrinho'])) {
  // Decodifique os dados do carrinho JSON
  $carrinho = json_decode($_POST['carrinho']);
  
  // Variável para armazenar o total da compra
  $total = 0;

  // Exiba os itens do carrinho na página de checkout
  foreach ($carrinho as $item) {
    // Exiba cada item do carrinho como necessário
    echo "Nome: $item->nome, Preço: $item->preco, Quantidade: $item->quantidade <br>";
    
    // Calcule o subtotal para cada item
    $subtotal = $item->preco * $item->quantidade;
    
    // Adicione o subtotal ao total da compra
    $total += $subtotal;
  }

  // Exiba o total da compra
  echo "Total da compra: $total";
}
?>




<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
  <h2 class="mb-4">Checkout</h2>
  <form method="post" action="salvar.php" accept-charset="UTF-8">
    <div class="form-group">
      <label for="fullName">Nome Completo</label>
      <input type="text" class="form-control" id="fullName" name="nomeCompleto" placeholder="Digite seu nome completo" required>
    </div>
    <div class="form-group">
      <label for="email">E-mail</label>
      <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
    </div>
    <div class="form-group">
      <label for="endereco">Endereço</label>
      <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Digite seu endereço" required>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="cidade">Cidade</label>
        <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Digite sua cidade" required>
      </div>
      <div class="form-group col-md-6">
        <label for="zipCode">CEP</label>
        <input type="text" class="form-control" id="zipCode" name="zipCode" placeholder="Digite seu CEP" required>
      </div>
    </div>

    <!-- Adicione um campo oculto para enviar dados do carrinho -->
    <input type="hidden" id="carrinhoInput" name="carrinho" value="">

    <div class="form-group">
      <label for="paymentMethod">Método de Pagamento</label>
      <select class="form-control" id="paymentMethod" name="paymentMethod" onchange="handlePaymentMethodChange()">
        <option value="Dinheiro">Pagamento na Entrega (Dinheiro)</option>
        <option value="pix">PIX</option>
        <option value="card">Cartão de Crédito</option>
      </select>
    </div>

    <!-- Campos específicos para cartão -->
    <div id="cardFields" style="display: none;">
      <div class="form-group">
        <label for="cardNumber">Número do Cartão</label>
        <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="Digite o número do seu cartão">
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="expirationDate">Data de Expiração</label>
          <input type="text" class="form-control" id="expirationDate" name="expirationDate" placeholder="MM/AA">
        </div>
        <div class="form-group col-md-6">
          <label for="cvv">CVV</label>
          <input type="text" class="form-control" id="cvv" name="cvv" placeholder="Digite o CVV">
        </div>
      </div>
    </div>

    <!-- Campos específicos para PIX -->
    <div id="pixFields" style="display: none;">
      <div class="form-group">
        <label for="pixKey">Chave PIX</label>
        <input type="text" class="form-control" id="pixKey" name="pixKey" placeholder="Digite sua chave PIX">
      </div>
    </div>

    <!-- ... (outros campos ou elementos do formulário) ... -->

    <!-- Botão de Submit -->
    <button type="submit" class="btn btn-dark">Finalizar Compra</button>
  </form>
</div>
<script>
  function handlePaymentMethodChange() {
    var paymentMethod = document.getElementById('paymentMethod').value;
    var cardFields = document.getElementById('cardFields');
    var pixFields = document.getElementById('pixFields');

    cardFields.style.display = paymentMethod === 'card' ? 'block' : 'none';
    pixFields.style.display = paymentMethod === 'pix' ? 'block' : 'none';
  }
</script>


<script>
 var paymentMethod = document.getElementById('paymentMethod').value;

// Definir carrinho como uma matriz vazia se não estiver definido
var carrinho = carrinho || [];

function handlePaymentMethodChange() {
  cardFields.style.display = 'none';
  pixFields.style.display = 'none';

  if (this.value === 'card') {
    cardFields.style.display = 'block';
  } else if (this.value === 'pix') {
    pixFields.style.display = 'block';
  }

  // Atualiza o campo oculto com todos os campos do formulário e do carrinho
  document.getElementById('carrinhoInput').value = JSON.stringify({
    fullName: document.getElementById('fullName').value,
    email: document.getElementById('email').value,
    address: document.getElementById('endereco').value,
    city: document.getElementById('cidade').value,
    zipCode: document.getElementById('zipCode').value,
    paymentMethod: getSelectedPaymentMethod(),
    cardNumber: document.getElementById('cardNumber').value,
    expirationDate: document.getElementById('expirationDate').value,
    cvv: document.getElementById('cvv').value,
    pixKey: document.getElementById('pixKey').value,
    carrinho: carrinho
  });

  if (document.getElementById('paymentMethod').value === '') {
    throw new Error('O campo "paymentMethod" está vazio.');
  }
}

function getSelectedPaymentMethod() {
  var paymentMethods = document.getElementsByName('paymentMethod');
  for (var i = 0; i < paymentMethods.length; i++) {
    if (paymentMethods[i].checked) {
      return paymentMethods[i].value;
    }
  }
  return ''; // Retorna vazio se nenhum método estiver selecionado
}

// Chame a função inicialmente para garantir que os campos estejam configurados corretamente
handlePaymentMethodChange();


</script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Bootstrap JS (incluindo Popper.js no Bootstrap 4) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    function buscarEnderecoPorCEP(cep) {
      var apiUrl = 'https://viacep.com.br/ws/' + cep + '/json/';

      fetch(apiUrl)
        .then(response => {
          if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.statusText}`);
          }
          return response.json();
        })
        .then(data => {
          if (data.erro) {
            throw new Error('CEP não encontrado');
          }
          document.getElementById('endereco').value = data.logradouro || '';
          document.getElementById('cidade').value = data.localidade || '';
        })
        .catch(error => console.error('Erro na requisição:', error));
    }

    document.getElementById('zipCode').addEventListener('change', function() {
      var cep = this.value.replace(/\D/g, '');

      if (cep.length === 8) {
        buscarEnderecoPorCEP(cep);
      }
    });

    // Adicione aqui o restante do seu código JavaScript, se necessário
  });
</script>

</body>
</html>