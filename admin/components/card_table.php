<table class="card-table table table-striped table-hover">
  <thead class="thead-dark">
    <tr>
      <th>ID</th>
      <th>Image</th>
      <th>Name</th>
      <th>Rarity</th>
      <th>Card Number</th>
      <th>Color</th>
      <th>Type</th>
      <th>Cost</th>
      <th>Power</th>
      <th>Subtype</th>
      <th>Attribute</th>
      <th>Artist</th>
      <th>Game - Set</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($cards as $card): ?>
      <tr>
        <td><?php echo $card['id']; ?></td>
        <td>
          <?php if (!empty($card['image_filename'])): ?>
            <img src="../../public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="Card image" class="img-thumbnail">
          <?php else: ?>
            <span class="text-muted">No image</span>
          <?php endif; ?>
        </td>
        <td><?php echo htmlspecialchars($card['name']); ?></td>
        <td><?php echo htmlspecialchars($card['rarity']); ?></td>
        <td><?php echo htmlspecialchars($card['card_number']); ?></td>
        <td><?php echo htmlspecialchars($card['color']); ?></td>
        <td><?php echo htmlspecialchars($card['card_type']); ?></td>
        <td><?php echo htmlspecialchars($card['cost']); ?></td>
        <td><?php echo htmlspecialchars($card['power']); ?></td>
        <td><?php echo htmlspecialchars($card['subtype']); ?></td>
        <td><?php echo htmlspecialchars($card['attribute']); ?></td>
        <td><?php echo htmlspecialchars($card['artist']); ?></td>
        <td>
          <?php echo htmlspecialchars($card['game_name']); ?> -
          <?php echo htmlspecialchars($card['set_name']); ?>
        </td>
        <td>
          <a href="#" class="btn btn-sm btn-primary edit-card" data-id="<?php echo $card['id']; ?>">Edit</a>
          <a href="#" class="btn btn-sm btn-danger delete-card" data-id="<?php echo $card['id']; ?>">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>