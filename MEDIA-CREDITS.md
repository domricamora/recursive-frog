# Media credits

## Background film

All clips in `public/media/video/` are from **[Mixkit](https://mixkit.co/free-license-video/)**
and are used under the **Mixkit Free License**, which permits commercial and
non-commercial use with no attribution required. The clips have been trimmed,
muted, scaled and re-encoded for the web; the originals remain the property of
their respective photographers.

| File        | Mixkit clip | Subject                                |
|-------------|-------------|----------------------------------------|
| `hero.mp4`  | `#917`      | Open office space and staircase        |
| `systems.mp4` | `#23282`  | Data centre hallway                    |
| `build.mp4` | `#29991`    | Engineer at a workbench                |
| `automate.mp4` | `#4835`  | Office workers at their devices        |
| `scale.mp4` | `#4809`     | Overhead view of a working table        |
| `ink.mp4`   | `#44818`    | Ink dispersing in water                |
| `clinic.mp4` | `#47020`   | Close-up of clinical instruments       |
| `consult.mp4` | `#42656`  | Consultant working at a screen         |

Each clip has a matching poster frame in `public/media/poster/`. The posters are
what a visitor sees before (or instead of) the film, so the page never flashes
a black box on a slow connection.

### Re-encoding the clips

`storage/app/encode-videos.ps1` documents the exact encode used. It is not part
of the application runtime; re-run it only if you want to re-cut a clip.

```powershell
php -d memory_limit=1G storage/app/make-preview.php   # helper, optional
pwsh -File storage/app/encode-videos.ps1
```

Encoding settings: H.264 High profile, `yuv420p`, CRF 31, `preset slow`,
`+faststart`, no audio, even height, 11–14 second loops.

## Typefaces

Self-hosted at build time by `laravel-vite-plugin` from the
[Bunny Fonts](https://fonts.bunny.net) mirror of Google Fonts. All four are
licensed under the **SIL Open Font License 1.1**.

- **Archivo** — display and UI
- **Newsreader** — editorial statements
- **Caveat** — signature hand
- **JetBrains Mono** — counters, dates and micro labels

Self-hosting keeps the first paint free of a third-party request and sends no
visitor data to a font CDN.

## Open Graph cover

`public/img/og-cover.png` is rendered from `public/img/og-cover.html` by
`storage/app/shot/og.mjs`, so it stays on exactly the same fonts and colours as
the site. Re-run `node storage/app/shot/og.mjs` after changing the wordmark.
