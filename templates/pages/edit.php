<div>
    <h3> Edycja Notatki </h3>
    <div>
        <?php if (!empty($params['note'])) : ?>
            <?php $note = $params['note'] ?>
            <form action="/?action=edit" method="POST" class="note-form">
                <input name="id" type="hidden" value="<?php echo $note['id']; ?>" />
                <ul>
                    <li>
                        <label>Tytuł <span class="required">*</span></label>
                        <input type="text" name="title" class="field-long" value="<?php echo $note['title']; ?>">
                    </li>
                    <li>
                        <label>Treść</label>
                        <textarea name="description" id="field5" class="field-long field-textarea"><?php echo $note['description']; ?></textarea>
                    </li>
                    <li>
                        <input type="submit" value="Submit">
                    </li>
                </ul>
            </form>
        <?php else : ?>
            <div>
                Brak danych do wyświetlenia
                <a href="/"> <button>Powrót</button></a>
            </div>
        <?php endif; ?>
    </div>
</div>