![Polyclip Hero](assets/polyclip-hero.jpg)

# Your Hero Banner Probably Doesn't Need 200KB of JavaScript

*A client needed animated hero banners. Their agency used 200KB of JavaScript. We used clip-path.*

## The bulldozer

A client wanted animated hero banners. The kind where an image sits inside a morphing polygon that slowly breathes and shifts. Their agency had built it with SVGator. It worked. It also loaded 200KB of JavaScript to animate what amounts to a decorative border.

SVGator is great software. It handles complex SVG timelines, easing curves, interactive triggers. But for a polygon that wobbles on a hero banner? That's a bulldozer for a flower bed.

We ripped it out and replaced it with CSS.

## 50 lines of CSS

CSS has `clip-path: polygon()`. You give it coordinates, it clips an element to that shape. Percentages make it responsive. No viewBox math, no JavaScript, no SVG DOM.

And because `clip-path` is animatable, you can morph between polygon states with plain `@keyframes`:

```css
@keyframes polyclip-morph-1 {
    0%   { clip-path: polygon(8% 35%, 53% 5%, 98% 48%, 83% 98%, 28% 92%) }
    33%  { clip-path: polygon(3% 58%, 30% 5%, 96% 25%, 76% 90%, 32% 98%) }
    66%  { clip-path: polygon(25% 28%, 52% 5%, 86% 42%, 74% 98%, 35% 72%) }
    100% { clip-path: polygon(8% 45%, 39% 20%, 67% 32%, 63% 86%, 30% 98%) }
}
```

The browser interpolates between them. Smooth morphing. Zero JavaScript. The GPU handles it.

## Turning it into a plugin

We packaged this as a WordPress plugin called **Polyclip**. Here's what's under the hood.

**Presets.** Nobody wants to hand-write polygon coordinates. We ship 6 presets: `default`, `angular-left`, `angular-right`, `wide`, `shard`, and `blob`. Each one is an array of polygon states that the CSS keyframes cycle through. The `blob` preset is a 9-point organic shape that looks like a living amoeba.

**Safe zone.** If your hero has a logo or focal point in the center, the morphing polygon might clip through it. The `safe_zone` parameter (0 to 70) clamps all vertices radially away from the center. The animation amplitude dampens proportionally so nothing oscillates into the protected area.

**Performance.** An `IntersectionObserver` pauses the animation when the banner scrolls out of view:

```js
var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
        var clip = entry.target.querySelector('.polyclip__clip--animated');
        if (!clip) return;
        if (entry.isIntersecting) {
            clip.classList.remove('polyclip__clip--paused');
        } else {
            clip.classList.add('polyclip__clip--paused');
        }
    });
}, { threshold: 0.05 });
```

This is the only JavaScript in the entire front-end. Everything else is CSS.

## Using it

Basic usage with the shortcode:

```
[polyclip image="123" preset="default"]
```

Multiple random images with the blob shape, slower animation:

```
[polyclip images="123,456,789" preset="blob" duration="12"]
```

Angular shard with a protected center area:

```
[polyclip image="123" preset="shard" safe_zone="30"]
```

If you're using Breakdance or Oxygen 6, there's a drag-and-drop element with visual controls. It registers automatically when those builders are active.

## When not to use it

Complex SVG path animations with multiple elements, interactive animations that respond to user input, anything that needs timeline control beyond looping. For those, SVGator or GSAP are the right tools. This is specifically for decorative polygon borders that breathe.

## The deal

We built this for a client project and realized there's no reason it should stay proprietary. It's a generic visual effect that any WordPress site could use.

We're not submitting it to the WordPress plugin repo. That comes with a support commitment we don't have bandwidth for. It's on GitHub, it works, the code is readable. If you find a bug, open an issue or fix it yourself. That's the deal.

---

> **GitHub:** [github.com/beatwiz/polyclip](https://github.com/beatwiz/polyclip)
> 
> 50 lines of CSS doing what 200KB of JavaScript used to do. Sometimes the boring solution is the right one.
