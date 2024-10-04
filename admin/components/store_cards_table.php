<table class="card-table table table-striped table-hover">
  <thead class="thead-dark">
    <tr>
      <th>ID</th>
      <th>Hình ảnh</th>
      <th>Tên</th>
      <?php foreach ($stores as $store_id): ?>
        <th><?php echo htmlspecialchars($cards[array_key_first($cards)]['prices'][$store_id]['store_name'] ?? ''); ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($cards as $card): ?>
      <tr>
        <td><?php echo $card['id']; ?></td>
        <td>
          <?php if (!empty($card['image_filename'])): ?>
            <img src="../assets/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="Hình ảnh thẻ bài" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
          <?php else: ?>
            <span class="text-muted">Không có hình ảnh</span>
          <?php endif; ?>
        </td>
        <td><?php echo htmlspecialchars($card['name']); ?></td>
        <?php foreach ($stores as $store_id): ?>
          <td>
            <?php
            if (isset($card['prices'][$store_id])) {
              $price_info = $card['prices'][$store_id];
              echo 'Giá: $' . number_format($price_info['price'], 2) . '<br>';
              echo 'SL: ' . $price_info['quantity'] . '<br>';
              echo 'Ship: $' . number_format($price_info['shipping'], 2);
            } else {
              echo 'N/A';
            }
            ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>