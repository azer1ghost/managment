#!/usr/bin/env python3
"""
PDF Signature Stamping Script
Stamps a PNG signature image onto a PDF file with human-like randomness.
"""

import argparse
import sys
import random
import os
from pathlib import Path

try:
    import fitz  # PyMuPDF
except ImportError:
    print("Error: PyMuPDF (fitz) is not installed. Please install it with: pip install pymupdf", file=sys.stderr)
    sys.exit(1)

try:
    from PIL import Image
except ImportError:
    print("Error: Pillow is not installed. Please install it with: pip install pillow", file=sys.stderr)
    sys.exit(1)


def remove_white_background(img, threshold=240):
    """
    Remove white background from image and make it transparent.
    Works for both PNG and JPEG images.
    
    Args:
        img: PIL Image object
        threshold: RGB threshold for white detection (0-255, default: 240)
    
    Returns:
        PIL Image with RGBA mode and transparent background
    """
    # Convert to RGBA if not already
    if img.mode != 'RGBA':
        img = img.convert('RGBA')
    
    # Get image data
    data = img.getdata()
    
    # Create new image data with transparent white pixels
    new_data = []
    for item in data:
        # If pixel is white (or near white), make it transparent
        if item[0] >= threshold and item[1] >= threshold and item[2] >= threshold:
            new_data.append((255, 255, 255, 0))  # Transparent
        else:
            new_data.append(item)  # Keep original
    
    # Update image with new data
    img.putdata(new_data)
    return img


def rotate_image(image_path, angle):
    """
    Rotate an image using Pillow and return the rotated image object.
    Preserves transparency (alpha channel) and removes white background for JPEG.
    
    Args:
        image_path: Path to the image file
        angle: Rotation angle in degrees (positive = counterclockwise)
    
    Returns:
        PIL Image object with transparency preserved
    """
    img = Image.open(image_path)
    
    # Remove white background (works for both PNG and JPEG)
    img = remove_white_background(img)
    
    # Rotate with expand=True to avoid cropping, use transparent background
    rotated = img.rotate(angle, expand=True, fillcolor=(0, 0, 0, 0))  # Transparent background
    return rotated


def stamp_pdf(input_pdf, signature_png, output_pdf, page_number=1):
    """
    Stamp a signature image onto a PDF page with human-like randomness.
    
    Args:
        input_pdf: Path to input PDF file
        signature_png: Path to signature PNG/JPG file
        output_pdf: Path to output PDF file
        page_number: Page number to stamp (1-indexed, default: 1)
    """
    # Open PDF
    doc = fitz.open(input_pdf)
    
    if page_number < 1 or page_number > len(doc):
        raise ValueError(f"Page number {page_number} is out of range (1-{len(doc)})")
    
    page = doc[page_number - 1]  # Convert to 0-indexed
    
    # Get page dimensions
    page_rect = page.rect
    page_width = page_rect.width
    page_height = page_rect.height
    
    # Configuration for bottom-left placement (sol aşağı - yumru yerin yaxınlığı)
    margin_left = -100   # Sol tərəfdən məsafə (çox sola - yumru mühürün yanında)
    margin_bottom = 70  # Aşağıdan məsafə (yumru mühürün olduğu yerə)
    box_w = 180        # Ölçü artırıldı (180-dən 220-yə)
    box_h = 130        # Ölçü artırıldı (130-dan 160-a)
    
    # Add human-like randomness
    jitter_x = random.uniform(-8, 4)  # Daha çox sola yönəldilmiş
    jitter_y = random.uniform(-6, 6)
    rotation = random.uniform(-3, 3)  # degrees
    scale_factor = random.uniform(0.97, 1.03)  # +/- 3%
    
    # Calculate position (bottom-left / sol aşağı)
    # In PyMuPDF: origin is top-left, y increases downward
    # Sol aşağı üçün: x kiçik, y böyük olmalıdır
    x0 = margin_left + jitter_x
    y0 = page_height - margin_bottom - box_h + jitter_y  # Aşağıdan başlayır
    
    # Apply scale
    scaled_w = box_w * scale_factor
    scaled_h = box_h * scale_factor
    
    # Ensure the signature stays within page bounds
    if x0 < 0:
        x0 = 0
    if y0 < 0:
        y0 = 0
    if x0 + scaled_w > page_width:
        x0 = page_width - scaled_w
    if y0 + scaled_h > page_height:
        y0 = page_height - scaled_h
    
    # Create rectangle for image placement
    rect = fitz.Rect(x0, y0, x0 + scaled_w, y0 + scaled_h)
    
    # Handle rotation and transparency
    # For both PNG and JPEG, we process the image to ensure transparency
    # PyMuPDF's insert_image doesn't support rotation directly,
    # so we process the image first using Pillow
    
    # Process image to remove white background and apply rotation if needed
    if abs(rotation) > 0.1:  # Only rotate if significant
        # Create temporary processed image with transparency
        processed_img = rotate_image(signature_png, rotation)
    else:
        # Just remove white background without rotation
        img = Image.open(signature_png)
        processed_img = remove_white_background(img)
    
    # Save processed image as PNG with transparency
    temp_processed_path = signature_png + '.processed_temp.png'
    processed_img.save(temp_processed_path, 'PNG')
    
    try:
        # Insert processed image (transparency will be preserved)
        page.insert_image(rect, filename=temp_processed_path, keep_proportion=True)
    finally:
        # Clean up temp file
        if os.path.exists(temp_processed_path):
            os.remove(temp_processed_path)
    
    # Save PDF
    doc.save(output_pdf)
    doc.close()
    
    print(f"Successfully stamped signature on page {page_number}")


def main():
    parser = argparse.ArgumentParser(description='Stamp a signature image onto a PDF')
    parser.add_argument('--input', required=True, help='Input PDF file path')
    parser.add_argument('--sig', required=True, help='Signature image file path (PNG/JPG)')
    parser.add_argument('--output', required=True, help='Output PDF file path')
    parser.add_argument('--page', type=int, default=1, help='Page number to stamp (default: 1)')
    
    args = parser.parse_args()
    
    # Validate input files exist
    if not os.path.exists(args.input):
        print(f"Error: Input PDF not found: {args.input}", file=sys.stderr)
        sys.exit(1)
    
    if not os.path.exists(args.sig):
        print(f"Error: Signature image not found: {args.sig}", file=sys.stderr)
        sys.exit(1)
    
    try:
        stamp_pdf(args.input, args.sig, args.output, args.page)
    except Exception as e:
        print(f"Error: {str(e)}", file=sys.stderr)
        sys.exit(1)


if __name__ == '__main__':
    main()
