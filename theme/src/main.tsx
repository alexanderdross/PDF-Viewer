import { createRoot } from "react-dom/client";
import App from "./App.tsx";


// Self-hosted fonts with font-display: swap (bundled with service worker)
import "@fontsource/outfit/400.css";
import "@fontsource/outfit/500.css";
import "@fontsource/outfit/600.css";
import "@fontsource/outfit/700.css";
import "@fontsource/outfit/800.css";
import "@fontsource/inter/400.css";
import "@fontsource/inter/500.css";
import "@fontsource/inter/600.css";

import "./index.css";

createRoot(document.getElementById("root")!).render(<App />);
