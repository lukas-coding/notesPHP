<div class="show">
    <?php $note = $params['note'] ?? null; ?>
    <?php if ($note) : ?>
        <ul>
            <li>Id: <?php echo $note['id']; ?></li>
            <li>Tutuł: <?php echo $note['title']; ?></li>
            <li>Opis: <?php echo $note['description']; ?></li>
            <li>Zapisano: <?php echo $note['created']; ?></li>
        </ul>
        <form method="POST" action="/?action=delete">
            <input type="hidden" value="<?php echo $note['id'] ?>" />
            <input type="submit" value="Usuń" style="background-color: #ff8080; cursor: pointer; padding: 5px 10px;
            font-size: 14px;">
        </form>
    <?php else : ?>
        <div>
            <p>Brak notatki do wyświetlenia</p>
        </div>
    <?php endif; ?>
    <a href="/"><button>Powrót</button></a>
</div>