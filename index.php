<?php
include 'includes/header.php';
include 'includes/banners.php';

$mainBanner = get_main_banner();
$subBanners = get_sub_banners();
?>
<!-- CONTAINER -->
<div class="container">
  <!-- BANNER -->
  <?php if ($mainBanner): ?>
    <div class="container__banner" style="background-image: url(<?php echo htmlspecialchars('/public/images/banner/' . $mainBanner['banner_img']); ?>);">
      <div class="banner-overlay"></div>
      <h1><?php echo htmlspecialchars($mainBanner['title']); ?></h1>
      <p><?php echo htmlspecialchars($mainBanner['subtitle']); ?></p>
      <span><?php echo htmlspecialchars($mainBanner['release_date']); ?></span>
      <button onclick="window.location.href='<?php echo htmlspecialchars($mainBanner['url']); ?>'">Preorder Now</button>
    </div>
  <?php endif; ?>
  <!-- SIGNUP -->
  <div class="container__signup">
    <a href="/views/login/sign_up.php"><i class="fa-regular fa-envelope"></i> Sign Up for Emails <i class='bx bxs-right-arrow'></i></a>
  </div>
  <!-- CONTENT -->
  <div class="container__content">
    <?php if (!empty($subBanners)): ?>
      <a href="<?php echo htmlspecialchars($subBanners[0]['url']); ?>" class="container__content-item">
        <img src="<?php echo htmlspecialchars('/public/images/banner/' . $subBanners[0]['banner_img']); ?>" alt="<?php echo htmlspecialchars($subBanners[0]['title']); ?>">
      </a>
      <div class="container__content-grid">
        <?php for ($i = 1; $i < count($subBanners); $i++): ?>
          <a href="<?php echo htmlspecialchars($subBanners[$i]['url']); ?>" class="container__content-item">
            <img src="<?php echo htmlspecialchars('/public/images/banner/' . $subBanners[$i]['banner_img']); ?>" alt="<?php echo htmlspecialchars($subBanners[$i]['title']); ?>">
          </a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
    <!-- LATEST SETS -->
    <h1 class="latest-title">Latest Sets</h1>
    <h2 class="latest-subtitle">New sets from your favorite games:</h2>
    <div class="latest-grid">
      <div class="latest-grid-item">
        <img loading="lazy" src="/public/images/banner/6.jpg" alt="">
        <div class="latest-grid-item-content">
          <span>SV07: Stellar Crown</span>
          <p>Pokemon</p>
          <button>Shop Now</button>
        </div>
      </div>
      <div class="latest-grid-item">
        <img loading="lazy" src="/public/images/banner/7.jpg" alt="">
        <div class="latest-grid-item-content">
          <span>Rosetta</span>
          <p>Flesh and Blood TCG</p>
          <button>Shop Now</button>
        </div>
      </div>
      <div class="latest-grid-item">
        <img loading="lazy" src="/public/images/banner/8.jpg" alt="">
        <div class="latest-grid-item-content">
          <span>Shimmering Skies</span>
          <p>Disney Lorcana</p>
          <button>Shop Now</button>
        </div>
      </div>
      <div class="latest-grid-item">
        <img loading="lazy" src="/public/images/banner/9.jpg" alt="">
        <div class="latest-grid-item-content">
          <span>Dimensional Transcendence</span>
          <p>Cardfight Vanguard</p>
          <button>Shop Now</button>
        </div>
      </div>
    </div>
    <!-- BEST SELLERS -->
    <h1 class="latest-title">Best Sellers</h1>
    <h2 class="latest-subtitle">Check out these hot products:</h2>
    <div class="best-sellers-grid">
      <div class="best-sellers-grid-item">
        <img loading="lazy" src="/public/images/banner/10.jpg" alt="">
        <div class="best-sellers-grid-item-content">
          <p>25th Anniversary Tin: Dueling Mirrors</p>
          <span>YuGiOh</span>
        </div>
      </div>
      <div class="best-sellers-grid-item">
        <img loading="lazy" src="/public/images/banner/11.jpg" alt="">
        <div class="best-sellers-grid-item-content">
          <p>Terapagos ex - 128/142
            SV07: Stellar Crown</p>
          <span>Pokemon</span>
        </div>
      </div>
      <div class="best-sellers-grid-item">
        <img loading="lazy" src="/public/images/banner/12.jpg" alt="">
        <div class="best-sellers-grid-item-content">
          <p>Stellar Crown Booster Box
            SV07: Stellar Crown</p>
          <span>Pokemon</span>
        </div>
      </div>
      <div class="best-sellers-grid-item">
        <img loading="lazy" src="/public/images/banner/13.jpg" alt="">
        <div class="best-sellers-grid-item-content">
          <p>Duskmourn: House of Horror - Commander Deck Display</p>
          <span>Magic: The Gathering</span>
        </div>
      </div>
      <div class="best-sellers-grid-item">
        <img loading="lazy" src="/public/images/banner/14.jpg" alt="">
        <div class="best-sellers-grid-item-content">
          <p>Two Legends - Booster Display
            Two Legends</p>
          <span>One Piece Card Game</span>
        </div>
      </div>
      <div class="best-sellers-grid-item">
        <img loading="lazy" src="/public/images/banner/15.jpg" alt="">
        <div class="best-sellers-grid-item-content">
          <p>Blue-Eyes White Dragon (Quarter Century Secret Rare)</p>
          <span>YuGiOh</span>
        </div>
      </div>
      <div class="best-sellers-grid-item">
        <img loading="lazy" src="/public/images/banner/16.jpg" alt="">
        <div class="best-sellers-grid-item-content">
          <p>Silvers Rayleigh Two Legends</p>
          <span>One Piece Card Game</span>
        </div>
      </div>
      <div class="best-sellers-grid-item">
        <img loading="lazy" src="/public/images/banner/17.jpg" alt="">
        <div class="best-sellers-grid-item-content">
          <p>Teal Mask Ogerpon ex - 025/167</p>
          <span>Pokemon</span>
        </div>
      </div>
      <div class="best-sellers-grid-item">
        <img loading="lazy" src="/public/images/banner/18.jpg" alt="">
        <div class="best-sellers-grid-item-content">
          <p>Duskmourn: House of Horror - Play Booster Display</p>
          <span>Magic: The Gathering</span>
        </div>
      </div>
      <div class="best-sellers-grid-item">
        <img loading="lazy" src="/public/images/banner/19.jpg" alt="">
        <div class="best-sellers-grid-item-content">
          <p>Innkeeper's Talent
            Bloomburrow</p>
          <span>Magic: The Gathering</span>
        </div>
      </div>

    </div>
  </div>
</div>
</div>
<!-- END CONTENT -->
</div>
<?php
include 'includes/footer.php';
?>