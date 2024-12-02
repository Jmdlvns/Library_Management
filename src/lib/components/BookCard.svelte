<script lang="ts">
  export let book: {
    id: number;
    title: string;
    author: string;
    isbn: string;
    genre: string;
    available_copies: number;
  };
</script>

<div class="bg-white shadow-md rounded-lg p-4 mb-4">
  <h2 class="text-xl font-semibold">{book.title}</h2>
  <p class="text-gray-600">by {book.author}</p>
  <p class="text-sm text-gray-500">ISBN: {book.isbn}</p>
  <p class="text-sm text-gray-500">Genre: {book.genre}</p>
  <p class="mt-2">Available Copies: {book.available_copies}</p>
</div>

