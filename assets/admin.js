function delete_url(id) {
    if (confirm('Are you sure to delete this record?')) {
        document.location = 'index.php?delete_id=' + id;
    }
}

