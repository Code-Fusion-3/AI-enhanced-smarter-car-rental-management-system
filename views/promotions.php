<?php require 'views/layouts/header.php'; ?>
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-blue-700 mb-6">All Promotions</h1>
    <?php
    require_once 'config/database.php';
    $db = new Database();
    $conn = $db->connect();
    $sql = "SELECT * FROM promotions WHERE is_active = 1 AND end_date >= CURDATE() ORDER BY start_date ASC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($promo = $result->fetch_assoc()) {
    ?>
    <div class="bg-white rounded-lg shadow p-6 mb-6 border border-blue-100">
        <div class="flex justify-between items-center">
            <div>
                <span class="text-yellow-400 font-bold"><?php echo $promo['code']; ?></span>
                <h2 class="text-xl font-semibold text-blue-800"><?php echo $promo['description']; ?></h2>
            </div>
            <div class="text-right">
                <?php if($promo['discount_percentage']): ?>
                <span class="text-2xl font-bold text-blue-700"><?php echo $promo['discount_percentage']; ?>%</span>
                <span class="text-blue-400 block text-sm">OFF</span>
                <?php elseif($promo['discount_amount']): ?>
                <span class="text-2xl font-bold text-blue-700">$<?php echo $promo['discount_amount']; ?></span>
                <span class="text-blue-400 block text-sm">OFF</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-2 text-sm text-blue-600">
            Valid until: <?php echo date('M d, Y', strtotime($promo['end_date'])); ?>
        </div>
    </div>
    <?php
        }
    } else {
    ?>
    <div class="bg-white rounded-lg shadow p-6 mb-6 border border-blue-100">
        <h2 class="text-blue-800 font-semibold">No active promotions at the moment</h2>
        <p class="text-blue-400 text-sm mt-1">Check back soon for new offers!</p>
    </div>
    <?php } ?>
</div>
<?php require 'views/layouts/footer.php'; ?>