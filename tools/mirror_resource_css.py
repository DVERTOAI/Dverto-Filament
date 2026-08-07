from pathlib import Path
import re
import sys

# Usage: python mirror_resource_css.py users doctors
# Adds .fi-resource-{target} wherever .fi-resource-{source} appears in selectors.

source = sys.argv[1] if len(sys.argv) > 1 else "users"
target = sys.argv[2] if len(sys.argv) > 2 else "doctors"
source_cls = f".fi-resource-{source}"
target_cls = f".fi-resource-{target}"

path = Path(r"c:\laragon\www\Dverto-Filament\resources\css\filament\admin\theme.css")
css = path.read_text(encoding="utf-8")

comments: list[str] = []


def stash_comment(match: re.Match[str]) -> str:
    comments.append(match.group(0))
    return f"___CSS_COMMENT_{len(comments) - 1}___"


css_no_comments = re.sub(r"/\*.*?\*/", stash_comment, css, flags=re.S)


def split_selectors(block: str) -> list[str]:
    items: list[str] = []
    buf: list[str] = []
    depth = 0
    for ch in block:
        if ch == "(":
            depth += 1
            buf.append(ch)
        elif ch == ")":
            depth = max(0, depth - 1)
            buf.append(ch)
        elif ch == "," and depth == 0:
            items.append("".join(buf))
            buf = []
        else:
            buf.append(ch)
    if buf:
        items.append("".join(buf))
    return items


COMMENT_TOKEN = re.compile(r"___CSS_COMMENT_\d+___")
pattern = re.compile(r"((?:^|})\s*)([^@{}][^{}]*)\{", re.M)
touched = 0


def repl(match: re.Match[str]) -> str:
    global touched
    prefix, selectors = match.group(1), match.group(2)

    leading_comments: list[str] = []
    rest = selectors
    while True:
        rest_lstrip = rest.lstrip()
        m = re.match(r"(___CSS_COMMENT_\d+___)\s*", rest_lstrip)
        if not m:
            break
        leading_comments.append(m.group(1))
        rest = rest_lstrip[m.end() :]

    if source_cls not in rest:
        return match.group(0)

    items: list[str] = []
    for item in split_selectors(rest):
        cleaned = COMMENT_TOKEN.sub("", item).strip()
        if not cleaned:
            continue
        items.append(cleaned)
        if source_cls in cleaned and target_cls not in cleaned:
            items.append(cleaned.replace(source_cls, target_cls))

    deduped: list[str] = []
    seen: set[str] = set()
    for item in items:
        key = re.sub(r"\s+", " ", item)
        if key not in seen:
            deduped.append(item)
            seen.add(key)

    touched += 1
    lead = "".join(f"{token}\n" for token in leading_comments)
    if "\n" in rest:
        return f"{prefix}{lead}{',\n'.join(deduped)} {{"
    return f"{prefix}{lead}{', '.join(deduped)} {{"


expanded = pattern.sub(repl, css_no_comments)
final = re.sub(
    r"___CSS_COMMENT_(\d+)___",
    lambda m: comments[int(m.group(1))],
    expanded,
)
path.write_text(final, encoding="utf-8")

stuck = len(
    re.findall(
        rf",\s*/\*.*?\*/\s*\n\{re.escape(target_cls)}",
        final,
        flags=re.S,
    )
)
print(f"source={source} target={target}")
print(f"rules_touched={touched}")
print(f"target_count={final.count(target_cls)}")
print(f"source_count={final.count(source_cls)}")
print(f"stuck_comments={stuck}")
