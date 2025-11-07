<?php
/**
 * File: includes/DAO_artikel.php
 * Data Access Object untuk tabel artikel
 */

class DAO_Artikel {
    private $conn;
    private $table_name = "artikel";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get artikel by ID
     */
    public function getById($id) {
        $query = "SELECT a.*, d.nama_lengkap as nama_dokter, d.foto_profil as foto_dokter
                  FROM " . $this->table_name . " a
                  LEFT JOIN dokter d ON a.id_dokter = d.id_dokter
                  WHERE a.id_artikel = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get artikel by slug
     */
    public function getBySlug($slug) {
        $query = "SELECT a.*, d.nama_lengkap as nama_dokter, d.spesialisasi, d.foto_profil as foto_dokter
                  FROM " . $this->table_name . " a
                  LEFT JOIN dokter d ON a.id_dokter = d.id_dokter
                  WHERE a.slug = :slug AND a.status = 'published' LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":slug", $slug);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all artikel
     */
    public function getAll($limit = null, $offset = 0) {
        $query = "SELECT a.*, d.nama_lengkap as nama_dokter, d.spesialisasi, d.foto_profil as foto_dokter
                  FROM " . $this->table_name . " a
                  LEFT JOIN dokter d ON a.id_dokter = d.id_dokter
                  WHERE a.status = 'published'
                  ORDER BY a.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($query);
        if ($limit) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get artikel by dokter
     */
    public function getByDokter($id_dokter, $limit = null, $offset = 0) {
        $query = "SELECT a.*, d.nama_lengkap as nama_dokter, d.spesialisasi, d.foto_profil as foto_dokter
                  FROM " . $this->table_name . " a
                  LEFT JOIN dokter d ON a.id_dokter = d.id_dokter
                  WHERE a.id_dokter = :id_dokter
                  ORDER BY a.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_dokter", $id_dokter);
        if ($limit) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get artikel by kategori
     */
    public function getByKategori($kategori, $limit = null, $offset = 0) {
        $query = "SELECT a.*, d.nama_lengkap as nama_dokter, d.spesialisasi, d.foto_profil as foto_dokter
                  FROM " . $this->table_name . " a
                  LEFT JOIN dokter d ON a.id_dokter = d.id_dokter
                  WHERE a.kategori = :kategori AND a.status = 'published'
                  ORDER BY a.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":kategori", $kategori);
        if ($limit) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get artikel by status
     */
    public function getByStatus($status, $limit = null, $offset = 0) {
        $query = "SELECT a.*, d.nama_lengkap as nama_dokter, d.spesialisasi, d.foto_profil as foto_dokter
                  FROM " . $this->table_name . " a
                  LEFT JOIN dokter d ON a.id_dokter = d.id_dokter
                  WHERE a.status = :status
                  ORDER BY a.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        if ($limit) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create new artikel
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . "
                  (id_dokter, judul, slug, kategori, konten, gambar, status, views)
                  VALUES (:id_dokter, :judul, :slug, :kategori, :konten, :gambar, :status, 0)";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind
        $id_dokter = $data['author_id'];
        $judul = htmlspecialchars(strip_tags($data['judul']));
        $slug = $this->createSlug($data['judul']);
        $kategori = $data['kategori'] ?? 'umum';
        $konten = $data['isi']; // Allow HTML for rich content
        $gambar = $data['gambar'] ?? null;
        $status = $data['status'] ?? 'draft';

        $stmt->bindParam(":id_dokter", $id_dokter);
        $stmt->bindParam(":judul", $judul);
        $stmt->bindParam(":slug", $slug);
        $stmt->bindParam(":kategori", $kategori);
        $stmt->bindParam(":konten", $konten);
        $stmt->bindParam(":gambar", $gambar);
        $stmt->bindParam(":status", $status);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Update artikel
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . "
                  SET judul = :judul, slug = :slug, kategori = :kategori, konten = :konten,
                      gambar = :gambar, status = :status
                  WHERE id_artikel = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind
        $judul = htmlspecialchars(strip_tags($data['judul']));
        $slug = $this->createSlug($data['judul']);
        $kategori = $data['kategori'];
        $konten = $data['konten'];
        $gambar = $data['gambar'] ?? null;
        $status = $data['status'] ?? 'draft';

        $stmt->bindParam(":judul", $judul);
        $stmt->bindParam(":slug", $slug);
        $stmt->bindParam(":kategori", $kategori);
        $stmt->bindParam(":konten", $konten);
        $stmt->bindParam(":gambar", $gambar);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    /**
     * Update status artikel
     */
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . "
                  SET status = :status
                  WHERE id_artikel = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    /**
     * Increment views
     */
    public function incrementViews($id) {
        $query = "UPDATE " . $this->table_name . "
                  SET views = views + 1
                  WHERE id_artikel = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    /**
     * Delete artikel
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_artikel = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    /**
     * Get total count
     */
    public function getTotalCount($status = null, $kategori = null) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE 1=1";

        $params = [];

        if ($status) {
            $query .= " AND status = :status";
            $params[':status'] = $status;
        }

        if ($kategori) {
            $query .= " AND kategori = :kategori";
            $params[':kategori'] = $kategori;
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    /**
     * Search artikel
     */
    public function search($keyword, $limit = null, $offset = 0) {
        $query = "SELECT a.*, d.nama_lengkap as nama_dokter, d.spesialisasi, d.foto_profil as foto_dokter
                  FROM " . $this->table_name . " a
                  LEFT JOIN dokter d ON a.id_dokter = d.id_dokter
                  WHERE (a.judul LIKE :keyword OR a.konten LIKE :keyword) AND a.status = 'published'
                  ORDER BY a.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($query);
        $keyword = "%" . $keyword . "%";
        $stmt->bindParam(":keyword", $keyword);
        if ($limit) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get popular artikel (by views)
     */
    public function getPopular($limit = 5) {
        $query = "SELECT a.*, d.nama_lengkap as nama_dokter, d.spesialisasi, d.foto_profil as foto_dokter
                  FROM " . $this->table_name . " a
                  LEFT JOIN dokter d ON a.id_dokter = d.id_dokter
                  WHERE a.status = 'published'
                  ORDER BY a.views DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create slug from title
     */
    private function createSlug($title) {
        // Convert to lowercase
        $slug = strtolower($title);

        // Replace non-letter or digits with -
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);

        // Remove multiple dashes
        $slug = preg_replace('/-+/', '-', $slug);

        // Trim dashes from beginning and end
        $slug = trim($slug, '-');

        return $slug;
    }

    /**
     * Check if slug exists
     */
    public function slugExists($slug, $exclude_id = null) {
        $query = "SELECT id_artikel FROM " . $this->table_name . " WHERE slug = :slug";
        if ($exclude_id) {
            $query .= " AND id_artikel != :exclude_id";
        }
        $query .= " LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":slug", $slug);
        if ($exclude_id) {
            $stmt->bindParam(":exclude_id", $exclude_id);
        }
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
?>
