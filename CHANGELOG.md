# Changelog

## 1.3.0 - 2026-11-05

### Fixed

- `Position::NORTH_EAST` and `Position::NORTH_WEST` had swapped values. `NORTH_EAST` is now `noea` (top right corner) and `NORTH_WEST` is now `nowe` (top left corner), the values the CDN uses. **This changes the URLs existing callers get:** a crop or watermark that asked for `NORTH_EAST` used to land in the top left corner and now lands in the top right, and the other way round for `NORTH_WEST`. Code that passed the raw strings `noea` or `nowe` is not affected.
- The `crop` resize type is now sent as `rt:fill`. The CDN has no `crop` resize type and answered `rt:crop` with a redirect to the original, unoptimized image.
- `WatermarkProperty` no longer drops the y offset and scale when an earlier offset is 0: `new WatermarkProperty(1, 0.5, 'soea', 0, 0, 0.4)` now gives `wm:1:0.5:soea:0:0:0.4` instead of `wm:1:0.5:soea`. Only trailing zeros are left out.

### Added

- `ResizeTypeProperty::FILL_DOWN`, `ResizeTypeProperty::FORCE` and `ResizeTypeProperty::AUTO`, the remaining resize types the CDN accepts.

### Deprecated

- `ResizeTypeProperty::CROP` and the `crop` resize type. Use `fill` instead. Passing `crop` triggers an `E_USER_DEPRECATED` notice.
