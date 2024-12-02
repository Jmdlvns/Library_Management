<script lang="ts">
  import { createEventDispatcher } from 'svelte';

  export let book: {
    id: number;
    title: string;
    author: string;
    isbn: string;
    genre: string;
    available_copies: number;
  };

  const dispatch = createEventDispatcher();

  function handleBorrow() {
    dispatch('borrow', book);
  }
</script>

<div class="bg-white shadow-md rounded-lg p-4 mb-4">
  <h2 class="text-xl font-semibold mb-2">Borrow: {book.title}</h2>
  <p class="mb-2">Available Copies: {book.available_copies}</p>
  <button on:click={handleBorrow} class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
    Borrow Book
  </button>
</div>

