import { writable } from 'svelte/store';

export interface Book {
  id: number;
  title: string;
  author: string;
  isbn: string;
  genre: string;
  availableCopies: number;
}

function createBookStore() {
  const { subscribe, set, update } = writable<Book[]>([]);

  return {
    subscribe,
    addBook: (book: Book) => update(books => [...books, book]),
    removeBook: (id: number) => update(books => books.filter(book => book.id !== id)),
    updateBook: (id: number, newData: Partial<Book>) => update(books => 
      books.map(book => book.id === id ? { ...book, ...newData } : book)
    ),
    setBooks: (books: Book[]) => set(books),
  };
}

export const bookStore = createBookStore();

