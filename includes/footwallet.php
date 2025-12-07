<p>
  <?php echo $T['crypto_desc']; ?>
</p>
<div class="logo-grid">
  <div class="logo-item">
    <img src="img/binance.png" alt="Binance">
    <span>Binance</span>
  </div>
  <div class="logo-item">
    <img src="img/okx.png" alt="OKX">
    <span>OKX</span>
  </div>
  <div class="logo-item">
    <img src="img/kraken.png" alt="Kraken">
    <span>Kraken</span>
  </div>
  <div class="logo-item">
    <img src="img/coinbase.png" alt="Coinbase">
    <span>Coinbase</span>
  </div>
  <div class="logo-item">
    <img src="img/metamask.png" alt="MetaMask">
    <span>MetaMask</span>
  </div>
  <div class="logo-item">
    <img src="img/kucoin.png" alt="KuCoin">
    <span>KuCoin</span>
  </div>
  <div class="logo-item">
    <img src="img/bitfinex.png" alt="Bitfinex">
    <span>Bitfinex</span>
  </div>
</div>

<style>
  .info-text {
    font-size: 14px;
    margin-bottom: 20px;
    line-height: 1.4;
    text-align: center;
  }

  .logo-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    /* 4 โลโก้ต่อแถว */
    gap: 20px;
    /* ช่องว่างระหว่างโลโก้ */
    justify-items: center;
    margin-bottom: 40px;
  }

  .logo-item {
    text-align: center;
    justify-items: center;
  }

  .logo-item img {
    width: 30px;
    /* ปรับขนาดโลโก้ */
    height: 30px;
    object-fit: contain;
    margin-bottom: 6px;
  }

  .logo-item span {
    font-size: 12px;
    color: #555;
    display: block;
  }
</style>