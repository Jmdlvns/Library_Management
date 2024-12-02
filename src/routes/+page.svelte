<script lang="ts">
  import { onMount } from 'svelte';
  import { goto } from '$app/navigation';

  interface User {
    id: number;
    name: string;
    email: string;
    role: 'admin' | 'borrower';
  }

  let email = '';
  let password = '';
  let user: User | null = null;
  let isLoading = false;

  onMount(() => {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      user = JSON.parse(storedUser);
    }
  });

  function validateInputs(): boolean {
    if (!email || !password) {
      alert('Please fill out all required fields.');
      return false;
    }
    return true;
  }

  async function login() {
    if (!validateInputs()) return;

    isLoading = true;
    try {
      const response = await fetch('api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
      });
      const result = await response.json();
      if (result.success) {
        user = result.user;
        localStorage.setItem('user', JSON.stringify(user));
        if (user?.role === 'admin') {
          goto('/books');
        } else if (user?.role === 'borrower') {
          goto('/borrower');
        }
      } else {
        alert(result.message);
      }
    } catch (error) {
      alert('Failed to connect to the server. Please try again later.');
      console.error(error);
    } finally {
      isLoading = false;
    }
  }

  function logout() {
    user = null;
    localStorage.removeItem('user');
    goto('/');
  }
</script>

{#if isLoading}
  <div class="loading-spinner text-center text-blue-600">
    Loading...
  </div>
{:else if user}
  <div class="text-center">
    <h1 class="text-3xl font-bold mb-4">Welcome to Library Book Management System</h1>
    <p>Please select a section from the navigation menu above.</p>
    <button on:click={logout} class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
      Logout
    </button>
  </div>
{:else}
  <form on:submit|preventDefault={login} class="max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4">Login</h2>
    <div class="mb-4">
      <label for="email" class="block mb-2">Email:</label>
      <input
        type="email"
        id="email"
        bind:value={email}
        required
        class="w-full px-3 py-2 border rounded"
      />
    </div>
    <div class="mb-4">
      <label for="password" class="block mb-2">Password:</label>
      <input
        type="password"
        id="password"
        bind:value={password}
        required
        class="w-full px-3 py-2 border rounded"
      />
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
      Login
    </button>
  </form>
{/if}
