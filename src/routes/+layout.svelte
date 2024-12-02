<script lang="ts">
	import { page } from '$app/stores';
	import { goto } from '$app/navigation';
	import { onMount } from 'svelte';
	import '../app.css';
   
	interface User {
	  id: number;
	  name: string;
	  email: string;
	  role: 'admin' | 'borrower';
	}
   
	let user: User | null = null;
   
	onMount(() => {
	  const storedUser = localStorage.getItem('user');
	  if (storedUser) {
	    user = JSON.parse(storedUser);
	  }
	});
   
	async function logout() {
	  user = null;
	  localStorage.removeItem('user');
	  goto('/');
	}
   </script>
   
   <nav class="bg-blue-600 p-4 text-white">
	<ul class="flex space-x-4">
	  <li><a href="/">Log in</a></li>
	  {#if user}
	    {#if user.role === 'admin'}
		 <li><a href="/books">Books</a></li>
		 <li><a href="/users">Users</a></li>
		 <li><a href="/fines">Fines</a></li>
	    {:else}
		 <li><a href="/borrower">Borrower Dashboard</a></li>
	    {/if}
	    <li><button on:click={logout}>Logout</button></li>
	  {/if}
	</ul>
   </nav>
   
   <main class="container mx-auto mt-8 p-4">
	<slot />
   </main>
   
   