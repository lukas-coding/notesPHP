<div class="list">
  <section>
    <div class="message error">
      <?php
      if (!empty($params['error'])) {
        switch ($params['error']) {
          case 'noteNotFound':
            echo 'Notatka nie została znaleziona !';
            break;
          case 'missingNoteId':
            echo 'Niepoprawne ID notatki';
            break;
        }
      }
      ?>
    </div>
    <div class="message">
      <?php
      if (!empty($params['before'])) {
        switch ($params['before']) {
          case 'created':
            echo 'Notatka została utworzona !!!';
            break;
          case 'edit':
            echo 'Notatka została zmieniona !!!';
            break;
          case 'delete':
            echo 'Notatka została usunięta';
            break;
        }
      }
      ?>
    </div>
    <?php dump($params['sort']);

    $sort = $params['sort'] ?? [];
    $by = $sort['by'] ?? 'title';
    $order = $sort['order'] ?? 'asc';

    ?>
    <div>
      <form class="settings-form" action="/" method="GET">
        <div>Sortuj po:</div>
        <div>
          <label>Tytule:<input name="sortby" type="radio" value="title" <?php echo $by === 'title' ? 'checked' : '' ?>></label>
          <label>Dacie:<input name="sortby" type="radio" value="created" <?php echo $by === 'created' ? 'checked' : '' ?>></label>
        </div>
        <div>Kierunek sortowania: </div>
        <div>
          <label>Rosnąco: <input name="orderby" type="radio" value="asc" <?php echo $order === 'asc' ? 'checked' : '' ?>></label>
          <label>Malejąco: <input name="orderby" type="radio" value="desc" <?php echo $order === 'desc' ? 'checked' : '' ?>></label>
        </div>
        <input type="submit" value="Sortuj" style="cursor: pointer; padding: 5px 10px;
            font-size: 14px;">
      </form>
    </div>

    <div class=" tbl-header">
      <table cellpadding='0' cellspacing='0' border='0'>
        <thead>
          <tr>
            <th>Lp</th>
            <th>Tytuł</th>
            <th>Data</th>
            <th>Opcje</th>
          </tr>
        </thead>
      </table>
    </div>
    <div class="tbl-content">
      <table cellpadding='0' cellspacing='0' border='0'>
        <tbody>
          <?php foreach ($params['notes'] ?? [] as $note) : ?>
            <tr>
              <td><?php echo $note['lp'] ?></td>
              <td><?php echo $note['title'] ?></td>
              <td><?php echo $note['created'] ?></td>
              <td>
                <a href="/?action=show&id=<?php echo $note['id'] ?>"> <button>Pokaż</button></a>
                <a href="/?action=delete&id=<?php echo $note['id'] ?>"> <button>Usuń</button> </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>