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
    <?php

    $sort = $params['sort'] ?? [];
    $by = $sort['by'] ?? 'title';
    $order = $sort['order'] ?? 'asc';
    $page = $params['page'] ?? [];
    $size = $page['size'] ?? 10;
    $currentPage = $page['number'] ?? 1;
    $pages = $page['pages'] ?? 1;

    $phrase = $params['phrase'] ?? null;
    ?>
    <div>
      <form class="settings-form" action="/" method="GET">
        <div>
          <label>Wyszukaj:<input type="text" name="phrase" value="<?php echo $phrase ?>">
        </div></label>


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
        <div>Ilość Danych</div>
        <div>
          <label>1<input type="radio" name="pagesize" value="1" <?php echo $size === 1 ? 'checked' : '' ?>></label>
          <label>5<input type="radio" name="pagesize" value="5" <?php echo $size === 5 ? 'checked' : '' ?>></label>
          <label>10<input type="radio" name="pagesize" value="10" <?php echo $size === 10 ? 'checked' : '' ?>></label>
          <label>25<input type="radio" name="pagesize" value="25" <?php echo $size === 25 ? 'checked' : '' ?>></label>
        </div>
        <input type="submit" value="Sortuj" style="cursor: pointer; padding: 5px 10px;font-size: 14px;">
      </form>
    </div>

    <div class=" tbl-header">
      <table cellpadding='0' cellspacing='0' border='0'>
        <thead>
          <tr>
            <th>Lp</th>
            <th>Id</th>
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
          <?php $n = 1;

          foreach ($params['notes'] ?? [] as $note) : ?>
            <tr>
              <td><?php echo $n++ ?></td>
              <td><?php echo $note['id'] ?></td>
              <td><?php echo $note['title'] ?></td>
              <td><?php echo $note['created'] ?></td>
              <td>
                <a href="/?action=show&id=<?php echo $note['id'] ?>"><button>Pokaż</button></a>
                <a href="/?action=delete&id=<?php echo $note['id'] ?>"><button>Usuń</button> </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php $paginationUrl = "&phrase=$phrase&pagesize=$size?sortby=$by&orderby=$order"; ?>

    <ul class="pagination">
      <?php if ($currentPage !== 1) : ?>
        <li>
          <a href="/?page=<?php echo $currentPage - 1 . $paginationUrl ?>">
            <button> &lt; &lt;</button>
          </a>
        </li>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $pages; $i++) : ?>
        <li>
          <a href=" /?page=<?php echo $i . $paginationUrl ?>">
            <button><?php echo $i ?></button>
          </a>
        </li>
      <?php endfor; ?>
      <?php if ($currentPage < $pages) : ?>
        <li>
          <a href="/?page=<?php echo $currentPage + 1 . $paginationUrl ?>">
            <button>&gt;&gt;</button>
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </section>
</div>