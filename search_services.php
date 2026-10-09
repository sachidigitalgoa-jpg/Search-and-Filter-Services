
<?php
$conn = new mysqli("localhost", "root", "", "sanctify_db");

$search = trim($_GET["search"] ?? "");
$category = trim($_GET["category"] ?? "");

$sql = "SELECT id, service_name, category, description, price
        FROM services
        WHERE service_name LIKE ?
        AND category LIKE ?
        ORDER BY service_name";

$stmt = $conn->prepare($sql);

$searchTerm = "%" . $search . "%";
$categoryTerm = $category === "" ? "%" : $category;

$stmt->bind_param("ss", $searchTerm, $categoryTerm);
$stmt->execute();

$result = $stmt->get_result();
?>

<form method="GET">
    <input name="search"
           placeholder="Search services"
           value="<?= htmlspecialchars($search) ?>">

    <select name="category">
        <option value="">All categories</option>
        <option value="Web">Web</option>
        <option value="Marketing">Marketing</option>
        <option value="Design">Design</option>
    </select>

    <button type="submit">Search</button>
</form>

<?php
while ($row = $result->fetch_assoc()) {
    echo "<h3>" . htmlspecialchars($row["service_name"]) . "</h3>";
    echo "<p>Category: " . htmlspecialchars($row["category"]) . "</p>";
    echo "<p>" . htmlspecialchars($row["description"] ?? "") . "</p>";
    echo "<p>Price: ₹" . number_format((float)$row["price"], 2) . "</p>";
}

$stmt->close();
$conn->close();
?>

// in db
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(150) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) DEFAULT 0
);

INSERT INTO services
(service_name, category, description, price)
VALUES
('Website Development', 'Web', 'Business website development', 15000),
('SEO Optimization', 'Marketing', 'Improve search visibility', 5000),
('Social Media Management', 'Marketing', 'Manage social media content', 8000),
('Graphic Design', 'Design', 'Create digital graphics', 3000);