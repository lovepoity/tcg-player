<?php
require_once '../../../includes/db_connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$subscriptions = [];

if ($user_id) {
  $stmt = $conn->prepare("SELECT * FROM email_pref WHERE user_id = :user_id");
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();
  $subscriptions = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}
?>

<div class="user-content">
  <h2 class="user-content__title">Email Preferences</h2>
  <div class="load--content">
    <h3>Game Updates</h3>
    <p>All the latest from your favorite games, from top sellers, to decklists, and official previews.</p>
    <div class="email__block">
      <div class="email__block--item">
        <label for="magic-the-gathering">
          <input type="checkbox" id="magic-the-gathering" name="subscriptions[magic_gathering]"
            <?php echo ($subscriptions['magic_gathering'] ?? 0) ? 'checked' : ''; ?>>
          Magic: The Gathering
        </label>
      </div>
      <div class="email__block--item">
        <label for="pokemon">
          <input type="checkbox" id="pokemon" name="subscriptions[pokemon]"
            <?php echo ($subscriptions['pokemon'] ?? 0) ? 'checked' : ''; ?>>
          Pokémon
        </label>
      </div>
      <div class="email__block--item">
        <label for="flesh-and-blood">
          <input type="checkbox" id="flesh-and-blood" name="subscriptions[flesh_blood]"
            <?php echo ($subscriptions['flesh_blood'] ?? 0) ? 'checked' : ''; ?>>
          Flesh and Blood
        </label>
      </div>
      <div class="email__block--item">
        <label for="digimon">
          <input type="checkbox" id="digimon" name="subscriptions[digimon]"
            <?php echo ($subscriptions['digimon'] ?? 0) ? 'checked' : ''; ?>>
          Digimon
        </label>
      </div>
      <div class="email__block--item">
        <label for="yu-gi-oh">
          <input type="checkbox" id="yu-gi-oh" name="subscriptions[yu_gi_oh]"
            <?php echo ($subscriptions['yu_gi_oh'] ?? 0) ? 'checked' : ''; ?>>
          Yu-Gi-Oh!
        </label>
      </div>
      <div class="email__block--item">
        <label for="disney-lorcana">
          <input type="checkbox" id="disney-lorcana" name="subscriptions[disney_lorcana]"
            <?php echo ($subscriptions['disney_lorcana'] ?? 0) ? 'checked' : ''; ?>>
          Disney Lorcana
        </label>
      </div>
      <div class="email__block--item">
        <label for="one-piece">
          <input type="checkbox" id="one-piece" name="subscriptions[one_piece]"
            <?php echo ($subscriptions['one_piece'] ?? 0) ? 'checked' : ''; ?>>
          One Piece
        </label>
      </div>
      <div class="email__block--item">
        <label for="star-wars">
          <input type="checkbox" id="star-wars" name="subscriptions[star_wars]"
            <?php echo ($subscriptions['star_wars'] ?? 0) ? 'checked' : ''; ?>>
          Star Wars: Unlimited
        </label>
      </div>
    </div>

    <h3>Magic Insider Tips</h3>
    <div class="email__block--2">
      <div class="email__block--item">
        <label for="channelfireball">
          <input type="checkbox" id="channelfireball" name="subscriptions[channel_fireball]"
            <?php echo ($subscriptions['channel_fireball'] ?? 0) ? 'checked' : ''; ?>>
          ChannelFireball (Competitive)
        </label>
      </div>
      <p>Crush the competition with monthly tips and tricks from Magic pros.</p>
      <div class="email__block--item">
        <label for="cassie-calls">
          <input type="checkbox" id="cassie-calls" name="subscriptions[cassie_calls]"
            <?php echo ($subscriptions['cassie_calls'] ?? 0) ? 'checked' : ''; ?>>
          Cassie's Calls (Finance)
        </label>
      </div>
      <p>In-depth market analysis and picks delivered weekly from our finance expert.</p>
    </div>

    <h3>Special Updates</h3>
    <div class="email__block--3">
      <div class="email__block--item">
        <label for="new-games">
          <input type="checkbox" id="new-games" name="subscriptions[new_games]"
            <?php echo ($subscriptions['new_games'] ?? 0) ? 'checked' : ''; ?>>
          New Games
        </label>
      </div>
      <p>Discover new titles and explore up-and-coming games.</p>
      <div class="email__block--item">
        <label for="promotions">
          <input type="checkbox" id="promotions" name="subscriptions[promotions]"
            <?php echo ($subscriptions['promotions'] ?? 0) ? 'checked' : ''; ?>>
          Promotions
        </label>
      </div>
      <p>Exclusive access to sales, exciting sweepstakes, and insider perks that you don't want to miss.</p>
      <div class="email__block--item">
        <label for="new-features">
          <input type="checkbox" id="new-features" name="subscriptions[new_features]"
            <?php echo ($subscriptions['new_features'] ?? 0) ? 'checked' : ''; ?>>
          New Features & More
        </label>
      </div>
      <p>Stay in the loop on new site features, products, and more.</p>
    </div>

    <div class="email--btn">
      <button class="btn--change" type="button" onclick="window.saveSubscriptions()">Save Changes</button>
      <button class="btn--unsubscribe" type="button" onclick="window.unsubscribeAll()">Unsubscribe From All</button>
    </div>
  </div>
</div>
<div id="user__toast" class="user__toast">
  <div class="user__toast-content">
    <p id="user__toast-message"></p>
  </div>
</div>