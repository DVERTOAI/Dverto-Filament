from pathlib import Path
import re

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

    # Pull leading comment tokens out of the selector list and keep them in prefix.
    leading_comments: list[str] = []
    rest = selectors
    while True:
        rest_lstrip = rest.lstrip()
        m = re.match(r"(___CSS_COMMENT_\d+___)\s*", rest_lstrip)
        if not m:
            break
        leading_comments.append(m.group(1))
        # preserve only the whitespace after removing the token from original rest start
        rest = rest_lstrip[m.end() :]

    if ".fi-resource-users" not in rest:
        return match.group(0)

    items: list[str] = []
    for item in split_selectors(rest):
        # Ignore any leftover comment tokens inside items
        cleaned = COMMENT_TOKEN.sub("", item).strip()
        if not cleaned:
            continue
        items.append(cleaned)
        if ".fi-resource-users" in cleaned and ".fi-resource-departments" not in cleaned:
            items.append(cleaned.replace(".fi-resource-users", ".fi-resource-departments"))

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
        body = ",\n".join(deduped)
        return f"{prefix}{lead}{body} {{"
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
        r",\s*/\*.*?\*/\s*\n\.fi-resource-departments",
        final,
        flags=re.S,
    )
)
print(f"rules_touched={touched}")
print(f"departments={final.count('.fi-resource-departments')}")
print(f"users={final.count('.fi-resource-users')}")
print(f"stuck_comments={stuck}")
