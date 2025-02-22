<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-md w-96">
            <h2 class="text-2xl font-bold mb-4">Register</h2>
            <form action="/api/users/register" method="post" >
                <div class="mb-4">
                    <label for="name" class="block text-gray400 font-bold mb-2">Name</label>
                    <input type="text" id="name" name="username" class="w-full px-3 py-2 ring-gray-300 border rounded-md focus:outline-none focus:ring-blue-500 border-gray-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray800 border-2 font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-blue-500 border-gray-500" required>
                </div>
                <div class="mb-4">
                    <label for="gender" class="block text-gray800 border-2 font-bold mb-2">Gender</label>
                    <select id="gender" name="gender" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-blue-500 border-gray-500" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray800 border-2 font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-blue-500 border-gray-500" required>
                </div>
                <div class="mb-4">
                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>