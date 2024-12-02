<script lang="ts">
    import { onMount } from 'svelte';
    import BookCard from '$lib/components/BookCard.svelte';
  
    interface Book {
      id: number;
      title: string;
      author: string;
      isbn: string;
      genre: string;
      total_copies: number;
      available_copies: number;
    }
  
    let books: Book[] = [];
    let newBook: Book = { id: 0, title: '', author: '', isbn: '', genre: '', total_copies: 1, available_copies: 1 };
  
    onMount(async () => {
      await fetchBooks();
    });
  
    async function fetchBooks() {
      const response = await fetch('localhost/Library-Management/backend/api/books.php');
      books = await response.json();
    }
  
    async function addBook() {
      const response = await fetch('localhost/Library-Management/backend/api/books.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(newBook)
      });
      const result = await response.json();
      if (result.message) {
        await fetchBooks();
        newBook = { id: 0, title: '', author: '', isbn: '', genre: '', total_copies: 1, available_copies: 1 };
      } else {
        alert(result.error);
      }
    }
  
    async function deleteBook(id: number) {
      const response = await fetch(`/api/books.php?id=${id}`, { method: 'DELETE' });
      const result = await response.json();
      if (result.message) {
        await fetchBooks();
      } else {
        alert(result.error);
      }
    }
  </script>
  
  <div>
    <h2 class="text-2xl font-semibold mb-4">Book Management</h2>
  
    <form on:submit|preventDefault={addBook} class="mb-4">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-6">
        <input bind:value={newBook.title} placeholder="Title" class="rounded-md border-gray-300 shadow-sm" required>
        <input bind:value={newBook.author} placeholder="Author" class="rounded-md border-gray-300 shadow-sm" required>
        <input bind:value={newBook.isbn} placeholder="ISBN" class="rounded-md border-gray-300 shadow-sm" required>
        <input bind:value={newBook.genre} placeholder="Genre" class="rounded-md border-gray-300 shadow-sm" required>
        <input bind:value={newBook.total_copies} type="number" min="1" placeholder="Total Copies" class="rounded-md border-gray-300 shadow-sm" required>
        <input bind:value={newBook.available_copies} type="number" min="1" placeholder="Available Copies" class="rounded-md border-gray-300 shadow-sm" required>
      </div>
      <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Book</button>
    </form>
  
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
      {#each books as book (book.id)}
        <div>
          <BookCard {book} />
          <button on:click={() => deleteBook(book.id)} class="mt-2 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
        </div>
      {/each}
    </div>
  </div>
  
  