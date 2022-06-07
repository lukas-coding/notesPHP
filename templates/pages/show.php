<div class="show">
    <?php $note = $params['note'] ?? null; ?>
    <?php if ($note) : ?>
        <ul>
            <li>Id: <?php echo ($note['id']); ?></li>
            <li>Tutuł: <?php echo htmlentities($note['title']); ?></li>
            <li>Opis: <?php echo htmlentities($note['description']); ?></li>
            <li>Zapisano: <?php echo htmlentities($note['created']); ?></li>
        </ul>
    <?php else : ?>
        <div>
            <p>Brak notatki do wyświetlenia</p>
        </div>
    <?php endif; ?>
    <a href="/"><button>Powrót</button></a>
</div>