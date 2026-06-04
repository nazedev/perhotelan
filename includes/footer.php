    </main>
</div>
<?php 
$parts = explode('/', $_SERVER['SCRIPT_NAME'] ?? '');
$base_url = (isset($parts[1]) && $parts[1] === 'pw') ? '/pw' : '';
?>
<script src="<?= $base_url ?>/js/script.js"></script>
</body>
</html>
