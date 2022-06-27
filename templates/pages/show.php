<div class="show">
    <?php $note = $params['note'] ?? null; ?>
    <?php if ($note) : ?>
        <ul>
            <li>Id: <?php echo $note['id']; ?></li>
            <li>Tutuł: <?php echo $note['title']; ?></li>
            <li>Opis: <?php echo $note['description']; ?></li>
            <li>Zapisano: <?php echo $note['created']; ?></li>
        </ul>
        <a href="/?action=edit&id=<?php echo $note['id'] ?>"><button>Edycja</button></a>
    <?php else : ?>
        <div>
            <p>Brak notatki do wyświetlenia</p>
        </div>
    <?php endif; ?>
    <a href="/"><button>Powrót</button></a>
</div>