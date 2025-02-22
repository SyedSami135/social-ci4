<!DOCTYPE html>
<html>

<head>
    <title>Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Posts</h1>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">User ID</th>
                    <th class="px-4 py-2">Post ID</th>
                    <th class="px-4 py-2">Title</th>
                    <th class="px-4 py-2">Content</th>
                </tr>
            </thead>
            <tbody id="post-data" class="justify-center text-center">
            </tbody>
        </table>
    </div>
    <script>
        fetch('/api/posts', {
                headers: {
                    'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJJc3N1ZXIgb2YgdGhlIEpXVCIsImF1ZCI6IkF1ZGllbmNlIHRoYXQgdGhlIEpXVCIsInN1YiI6IlN1YmplY3Qgb2YgdGhlIEpXVCIsImlhdCI6MTczOTg4MzE4NSwiZXhwIjoxNzM5OTY5NTg1LCJpZCI6IjUifQ.K9Oca_su0OHDaHh-C_wGHYEt-t1EJW-zHsNlhmFBHhY'
                }
            })

            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('post-data');
                console.log(data);
                data.data.forEach(post => {
                    const row = tbody.insertRow();
                    row.insertCell().textContent = post.user_id;
                    row.insertCell().textContent = post.id;
                    row.insertCell().textContent = post.title;
                    row.insertCell().textContent = post.content;
                });

            });
    </script>
</body>

</html>