<?php
// On simule une vérification de session d'admin
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Accès refusé. Seuls les administrateurs peuvent accéder à cette page.");
}

// Fichiers que l'admin a le droit de modifier (pour la démo)
$editable_files = [
    'config-site.php',
    'assets/style.css' 
];

$selected_file = '';
$file_content = '';

// --- LOGIQUE DE SAUVEGARDE (LA FAILLE EST ICI) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_to_save']) && isset($_POST['file_content'])) {
    $file_to_save = $_POST['file_to_save'];
    
    // Dans un vrai site, il y aurait plus de vérifications. Ici, on permet d'écrire.
    if (in_array($file_to_save, $editable_files)) {
        file_put_contents($file_to_save, $_POST['file_content']);
        echo "<p style='color: limegreen;'>Fichier $file_to_save sauvegardé avec succès !</p>";
    }
}

// --- LOGIQUE D'AFFICHAGE ---
if (isset($_GET['file'])) {
    $selected_file = $_GET['file'];
    if (in_array($selected_file, $editable_files) && file_exists($selected_file)) {
        // htmlspecialchars est utilisé pour afficher le contenu sans l'exécuter dans le textarea
        $file_content = htmlspecialchars(file_get_contents($selected_file));
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Éditeur de Fichiers</title>
    <!-- Tu peux ajouter les mêmes styles que tes autres pages ici -->
    <style>body{font-family:sans-serif; background-color:#0D1B2A; color:#E0E1DD; padding: 20px;} select, textarea, button{width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #415A77; background-color: #1B263B; color: #E0E1DD;} button{background-color:#00FFFF; color:#0D1B2A; font-weight: bold; cursor: pointer;}</style>
</head>
<body>
    <p><a href="admin.php" style="color: #00FFFF;">&larr; Retour au tableau de bord Admin</a></p>
    <h1>Éditeur de Thème/Configuration</h1>
    <p>Sélectionnez un fichier à modifier :</p>

    <form method="GET" action="admin_editor.php">
        <select name="file" onchange="this.form.submit()">
            <option>-- Choisissez un fichier --</option>
            <?php foreach ($editable_files as $file): ?>
                <option value="<?php echo $file; ?>" <?php if ($file === $selected_file) echo 'selected'; ?>>
                    <?php echo $file; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($selected_file): ?>
        <hr style="margin-top: 20px;">
        <h2>Modification de : <?php echo $selected_file; ?></h2>
        <form method="POST" action="admin_editor.php">
            <input type="hidden" name="file_to_save" value="<?php echo $selected_file; ?>">
            <textarea name="file_content" rows="20"><?php echo $file_content; ?></textarea>
            <button type="submit">Sauvegarder les modifications</button>
        </form>
    <?php endif; ?>
</body>
</html>