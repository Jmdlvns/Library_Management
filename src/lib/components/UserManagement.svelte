<script lang="ts">
  import { onMount } from 'svelte';

  interface User {
    id: number;
    name: string;
    email: string;
    role: 'borrower' | 'admin';
  }

  let users: User[] = [];
  let newUser: User = { id: 0, name: '', email: '', role: 'borrower' };

  onMount(async () => {
    await fetchUsers();
  });

  async function fetchUsers() {
    const response = await fetch('localhost/Library-Management/backend/api/users.php');
    users = await response.json();
  }

  async function addUser() {
    const response = await fetch('localhost/Library-Management/backend/api/users.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(newUser)
    });
    const result = await response.json();
    if (result.message) {
      await fetchUsers();
      newUser = { id: 0, name: '', email: '', role: 'borrower' };
    } else {
      alert(result.error);
    }
  }

  async function deleteUser(id: number) {
    const response = await fetch(`localhost/Library-Management/backend/api/users.php?id=${id}`, { method: 'DELETE' });
    const result = await response.json();
    if (result.message) {
      await fetchUsers();
    } else {
      alert(result.error);
    }
  }
</script>

<div class="bg-white shadow-md rounded-lg p-4 mb-4">
  <h2 class="text-2xl font-semibold mb-4">User Management</h2>
  
  <form on:submit|preventDefault={addUser} class="mb-4">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
      <input bind:value={newUser.name} placeholder="Name" class="rounded-md border-gray-300 shadow-sm" required>
      <input bind:value={newUser.email} type="email" placeholder="Email" class="rounded-md border-gray-300 shadow-sm" required>
      <select bind:value={newUser.role} class="rounded-md border-gray-300 shadow-sm">
        <option value="borrower">Borrower</option>
        <option value="admin">Admin</option>
      </select>
    </div>
    <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add User</button>
  </form>

  <table class="w-full">
    <thead>
      <tr>
        <th class="text-left">Name</th>
        <th class="text-left">Email</th>
        <th class="text-left">Role</th>
        <th class="text-left">Action</th>
      </tr>
    </thead>
    <tbody>
      {#each users as user (user.id)}
        <tr>
          <td>{user.name}</td>
          <td>{user.email}</td>
          <td>{user.role}</td>
          <td>
            <button on:click={() => deleteUser(user.id)} class="text-red-500 hover:text-red-700">Delete</button>
          </td>
        </tr>
      {/each}
    </tbody>
  </table>
</div>

